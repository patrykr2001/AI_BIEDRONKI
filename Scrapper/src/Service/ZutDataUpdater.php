<?php

namespace App\Service;

use App\Config\ConfigReader;
use App\Entity\DataUpdateLog;
use App\Entity\Group;
use App\Entity\Lesson;
use App\Entity\Room;
use App\Entity\Subject;
use App\Entity\Teacher;
use App\Enum\DataUpdateTypes;
use App\Enum\LessonForms;
use App\Enum\LessonStatuses;
use App\Enum\ZutDataKinds;
use App\Enum\ZutScheduleDataKinds;
use App\Utils\DateHelper;
use App\Utils\ZutUrlBuilder;
use DateTime;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ZutDataUpdater
{
    private HttpClientInterface $client;
    private ZutUrlBuilder $urlBuilder;
    private OutputInterface $output;
    private RoomService $roomService;
    private TeacherService $teacherService;
    private SubjectService $subjectService;
    private DataUpdateLogService $dataUpdateLogService;
    private LessonService $lessonService;
    private GroupService $groupService;
    private StudentService $studentService;

    public function __construct(HttpClientInterface $client, RoomService $roomService, TeacherService $teacherService,
                                SubjectService      $subjectService, DataUpdateLogService $dataUpdateLogService,
                                LessonService       $lessonService, GroupService $groupService,
                                StudentService      $studentService)
    {
        $url = (new ConfigReader())->getApiBaseUrl();
        $this->urlBuilder = new ZutUrlBuilder($url);
        $this->client = $client;
        $this->roomService = $roomService;
        $this->teacherService = $teacherService;
        $this->subjectService = $subjectService;
        $this->dataUpdateLogService = $dataUpdateLogService;
        $this->lessonService = $lessonService;
        $this->groupService = $groupService;
        $this->studentService = $studentService;
    }

    public function updateOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    public function updateZutData(): void
    {
        $this->updateMonthlyData();
        $this->updateWeeklyData();
        $this->updateDailyData();
    }

    /***
     * Updates monthly data if last update was more than 30 days ago.
     * Monthly data includes teachers, groups, subjects, rooms and lessons for the semester.
     */
    private function updateMonthlyData(): void
    {
        $lastMontlyDataUpdate = $this->dataUpdateLogService->findLastByType(DataUpdateTypes::Monthly);
        if ($lastMontlyDataUpdate === null) {
            $lastMontlyDataUpdate = new DataUpdateLog();
            $lastMontlyDataUpdate->setType(DataUpdateTypes::Monthly);
            $lastMontlyDataUpdate->setUpdateDate(DateHelper::getDate31DaysAgo());
        }

        if ($lastMontlyDataUpdate !== null) {
            $lastUpdateDate = $lastMontlyDataUpdate->getUpdateDate();
            $currentDate = DateHelper::getCurrentDay();
            $diff = $currentDate->diff($lastUpdateDate);
            if ($diff->days < 30) {
                $this->output->writeln('<info>There is no need to update monthly data. Last update ' . $lastMontlyDataUpdate->getUpdateDate()->format('y-m-d') . '. Days from last update ' . $diff->days . '</info>');
            } else {
                $this->output->writeln('<info>Updating monthly data...</info>');
                $this->updateSpecificZutData(ZutDataKinds::Teachers);
//                  $this->updateSpecificZutData(ZutDataKinds::Groups);
                $this->updateSpecificZutData(ZutDataKinds::Subjects);
                $this->updateSpecificZutData(ZutDataKinds::Rooms);
                $config = new ConfigReader();
                $startDate = new DateTime($config->getDateRange()['start']);
                $endDate = new DateTime($config->getDateRange()['end']);
                $this->updateTeachersScheduleData($startDate, $endDate);
                $this->updateStudentsGroupsData($startDate, $endDate, false);
                $lastMontlyDataUpdate = new DataUpdateLog();
                $lastMontlyDataUpdate->setType(DataUpdateTypes::Monthly);
                $lastMontlyDataUpdate->setUpdateDate(DateHelper::getCurrentDay());
                $this->dataUpdateLogService->save($lastMontlyDataUpdate);
            }
        }
    }

    /***
     * Updates weekly data if last update was more than 1 days ago.
     * Weekly data includes lessons for the current and next week.
     */
    private function updateWeeklyData(): void
    {
        $lastWeeklyDataUpdate = $this->dataUpdateLogService->findLastByType(DataUpdateTypes::Weekly);
        if ($lastWeeklyDataUpdate === null) {
            $lastWeeklyDataUpdate = new DataUpdateLog();
            $lastWeeklyDataUpdate->setType(DataUpdateTypes::Weekly);
            $lastWeeklyDataUpdate->setUpdateDate(DateHelper::getDateYesterday());
        }
        if ($lastWeeklyDataUpdate !== null) {
            $lastUpdateDate = $lastWeeklyDataUpdate->getUpdateDate();
            $currentDate = DateHelper::getTodayStart();
            $diff = $currentDate->diff($lastUpdateDate);
            if ($diff->days < 1) {
                $this->output->writeln('<info>There is no need to update weekly data. Last update ' . $lastWeeklyDataUpdate->getUpdateDate()->format('y-m-d') . '. Days from last update ' . $diff->days . '</info>');
            } else {
                $this->output->writeln('<info>Updating weekly data...</info>');
                $currentDate = DateHelper::getCurrentWeek();
                $this->updateTeachersScheduleData($currentDate[0], $currentDate[1]);
//                $this->updateStudentsGroupsData($currentDate[0], $currentDate[1]);
                $currentDate = DateHelper::getNextWeek();
                $this->updateTeachersScheduleData($currentDate[0], $currentDate[1]);
//                $this->updateStudentsGroupsData($currentDate[0], $currentDate[1]);
                $lastWeeklyDataUpdate = new DataUpdateLog();
                $lastWeeklyDataUpdate->setType(DataUpdateTypes::Weekly);
                $lastWeeklyDataUpdate->setUpdateDate(DateHelper::getTodayStart());
                $this->dataUpdateLogService->save($lastWeeklyDataUpdate);
            }
        }
    }

    /***
     * Updates daily data if last update was more than 1 hour ago.
     * Daily data includes lessons for the current day.
     */
    private function updateDailyData(): void
    {
        $lastDailyDataUpdate = $this->dataUpdateLogService->findLastByType(DataUpdateTypes::Daily);
        if ($lastDailyDataUpdate === null) {
            $lastDailyDataUpdate = new DataUpdateLog();
            $lastDailyDataUpdate->setType(DataUpdateTypes::Daily);
            $lastDailyDataUpdate->setUpdateDate(DateHelper::getTodayWithHourOneHourAgo());
        }
        if ($lastDailyDataUpdate !== null) {
            $lastUpdateDate = $lastDailyDataUpdate->getUpdateDate();
            $currentDate = DateHelper::getCurrentDay();
            $diff = $currentDate->diff($lastUpdateDate);
            if ($diff->h < 1) {
                $this->output->writeln('<info>There is no need to update daily data. Last update '
                    . $lastDailyDataUpdate->getUpdateDate()->format('y-m-d H:i') . '. Minutes from last update: '
                    . $diff->i . '</info>');
            } else {
                $this->output->writeln('<info>Updating daily data...</info>');
                $this->updateTeachersScheduleData(DateHelper::getTodayStart(), DateHelper::getTommorowStart());
//                $this->updateStudentsGroupsData(DateHelper::getTodayStart(), DateHelper::getTommorowStart());
                $lastDailyDataUpdate = new DataUpdateLog();
                $lastDailyDataUpdate->setType(DataUpdateTypes::Daily);
                $lastDailyDataUpdate->setUpdateDate(DateHelper::getTodayWithHour());
                $this->dataUpdateLogService->save($lastDailyDataUpdate);
            }
        }
    }

    private function updateTeachersScheduleData(DateTime $start, Datetime $end): void
    {
        $teachers = [];
//        $teachers[] = 'Abramek Karol';
        $teachers = array_map(fn($teacher) => $teacher->getName(), $this->teacherService->getAllTeachers());
        $this->updateSpecificTeachersScheduleData($teachers, $start, $end);
    }

    private function updateStudentsGroupsData(Datetime $start, Datetime $end, bool $onlyNew = true): void
    {
        $students = [];
//        $students[] = '51399';
        $students = array_map(fn($student) => $student->getNumber(), $this->studentService->getAllStudents());

        $this->updateSpecificStudentGroups($students, $start, $end, $onlyNew);
    }

    private function updateSpecificZutData(ZutDataKinds $kind): void
    {
        $this->output->writeln('<info>Fetching ' . $kind->name . ' data from API...</info>');

        $response = null;

        for ($i = 0; $i < 10; $i++) {
            try {
                $response = $this->client->request('GET', $this->urlBuilder->buildDataUrl($kind, ''), [
                    'headers' => [
                        'Accept-Charset' => 'UTF-8',
                    ],
                ]);
                if ($response->getStatusCode() === 200) {
                    break;
                }
            } catch (\Throwable $e) {
                $this->output->writeln('<error>Failed to fetch ' . $kind->name . ' data from API. Trying again in 5 seconds.</error>');
                sleep(5);
            }
        }

        $data = null;
        try {
            $data = $response->getContent();
        } catch (\Throwable $e) {
            $this->output->writeln('<error>Failed to fetch ' . $kind->name . ' data from API.</error>');
            return;
        }

        $this->output->writeln('<info>Successfully fetched ' . $kind->name . ' data from API.</info>');

        $this->output->writeln('<info>Processing ' . $kind->name . ' data...</info>');

        $processedData = $this->processJsonData($data);
        $data = null;

        $this->output->writeln('<info>Saving ' . $kind->name . ' data...</info>');

        $objects = [];

        switch ($kind) {
            case ZutDataKinds::Teachers:
                foreach ($processedData as $item) {
                    $teacher = new Teacher();
                    $teacher->setName($item['item']);
                    $objects[] = $teacher;
                }
                $this->teacherService->saveNewTeachers($objects);
                break;
            case ZutDataKinds::Subjects:
                foreach ($processedData as $item) {
                    $subject = new Subject();
                    $value = $this->getSubstringBeforeParenthesis($item['item']);
                    $subject->setName($value);
                    $objects[] = $subject;
                }
                $this->subjectService->saveNewSubjects($objects);
                break;
            case ZutDataKinds::Rooms:
                foreach ($processedData as $item) {
                    $room = new Room();
                    $room->setName($item['item']);
                    $objects[] = $room;
                }
                $this->roomService->saveNewRooms($objects);
                break;
        }

        $objects = null;

        $this->output->writeln('<info>Data successfully fetched and saved ' . $kind->name . ' data.</info>');
        gc_collect_cycles();
    }

    function getSubstringBeforeParenthesis(string $input): string
    {
        $position = strpos($input, '(');
        if ($position === false) {
            return trim($input);
        }
        return trim(substr($input, 0, $position));
    }

    private function updateSpecificStudentGroups(array $students, DateTime $start, DateTime $end, bool $onlyNew = true): void
    {
        foreach ($students as $studentString) {
            $data = [ZutScheduleDataKinds::Student->value => $studentString];

            $this->output->writeln('<info>Fetching ' . $studentString . ' groups data from API...</info>');

            $response = null;

            for ($i = 0; $i < 10; $i++) {
                try {
                    $response = $this->client->request('GET', $this->urlBuilder
                        ->buildScheduleUrl($data, $start, $end), [
                        'headers' => [
                            'Accept-Charset' => 'UTF-8',
                        ],
                    ]);

                    if ($response->getStatusCode() === 200) {
                        break;
                    }
                } catch (\Throwable $e) {
                    $this->output->writeln('<error>Failed to fetch ' . $studentString . ' groups data from API. Trying again in 5 seconds.</error>');
                    sleep(5);
                }
            }

            $data = null;

            try {
                $data = $response->getContent();
            } catch (\Throwable $e) {
                $this->output->writeln('<error>Failed to fetch ' . $studentString . ' groups data from API.</error>');
                return;
            }
            $this->output->writeln('<info>Successfully fetched ' . $studentString . ' groups data from API.</info>');

            $this->output->writeln('<info>Processing ' . $studentString . ' groups data...</info>');

            $processedData = $this->processJsonData($data);
            if (count($processedData) === 0) {
                $this->output->writeln('<info>No data to process for ' . $studentString . ' groups data.</info>');
                continue;
            }

            $this->output->writeln('<info>Saving ' . $studentString . ' groups data...</info>');

            $objects = [];

            foreach ($processedData as $item) {
                $group = null;
                if ($item['group_name'] !== null) {
                    $groupName = $item['group_name'];
                    if ($this->groupService->getGroupByName($groupName) !== null) {
                        $group = $this->groupService->getGroupByName($groupName);
                    } else {
                        $group = new Group();
                        $group->setName($groupName);
                        $this->groupService->save($group);
                    }
                }
                if ($group !== null) {
                    $objects[] = $group;
                }
            }

            $this->studentService->updateGroups($objects, $studentString, $onlyNew);

            $data = null;
            $processedData = null;
            $objects = null;

            $this->output->writeln('<info>Data successfully fetched and saved ' . $studentString
                . ' groups data.</info>');
            gc_collect_cycles();
        }
    }

    private function updateSpecificTeachersScheduleData(array $teachers, DateTime $start, DateTime $end): void
    {
        $dataCount = 0;

        foreach ($teachers as $teacherString) {
            $data = [ZutScheduleDataKinds::Teachers->value => $teacherString];

            $this->output->writeln('<info>Fetching ' . $teacherString . ' schedule data from API...</info>');

            $response = null;

            for ($i = 0; $i < 10; $i++) {
                try {
                    $response = $this->client->request('GET', $this->urlBuilder
                        ->buildScheduleUrl($data, $start, $end), [
                        'headers' => [
                            'Accept-Charset' => 'UTF-8',
                        ],
                    ]);

                    if ($response->getStatusCode() === 200) {
                        break;
                    }
                } catch (\Throwable $e) {
                    $this->output->writeln('<error>Failed to fetch ' . $teacherString . ' schedule data from API. Trying again in 5 seconds.</error>');
                    sleep(5);
                }
            }

            $data = null;

            try {
                $data = $response->getContent();
            } catch (\Throwable $e) {
                $this->output->writeln('<error>Failed to fetch ' . $teacherString . ' schedule data from API.</error>');
                return;
            }
            $this->output->writeln('<info>Successfully fetched ' . $teacherString . ' schedule data from API.</info>');

            $this->output->writeln('<info>Processing ' . $teacherString . ' schedule data...</info>');

            $processedData = $this->processJsonData($data);
            if (count($processedData) === 0) {
                $this->output->writeln('<info>No data to process for ' . $teacherString . ' schedule data.</info>');
                continue;
            }

            $objects = [];

            $this->output->writeln('<info>Saving ' . $teacherString . ' schedule data in number of ' . count($processedData) . '...</info>');
            $dataCount += count($processedData);

            foreach ($processedData as $item) {
                $teacher = $this->teacherService->getTeacherByName($item['worker']);
                $lessonStatus = $this->mapLessonStatus($item['status_item']);
                $hours = $item['hours'];
                $startDate = new DateTime($item['start']);
                $endDate = new DateTime($item['end']);

                $teacherCover = null;
                if ($item['worker_cover'] !== null && $item['worker_cover'] !== '') {
                    $teacherCover = $this->teacherService->getTeacherByName($item['worker_cover']);
                }
                $room = null;
                if ($item['room'] !== null && $item['room'] !== '') {
                    $room = $this->roomService->getRoomByName($item['room']);
                }
                $lessonForm = null;
                if ($item['lesson_form'] !== null && $item['lesson_form'] !== '') {
                    $lessonForm = $this->mapLessonForm($item['lesson_form']);
                }

                $group = null;
                if ($item['group_name'] !== null) {
                    $groupName = $item['group_name'];
                    if ($this->groupService->getGroupByName($groupName) !== null) {
                        $group = $this->groupService->getGroupByName($groupName);
                    } else {
                        $group = new Group();
                        $group->setName($groupName);
                        $this->groupService->save($group);
                    }
                }
                $subject = null;
                if ($item['subject'] !== null) {
                    $subjectName = $this->capitalizeFirstLetterOfEachWord($item['subject']);
                    if ($this->subjectService->getSubjectByName($subjectName) !== null) {
                        $subject = $this->subjectService->getSubjectByName($subjectName);
                    } else {
                        $subject = new Subject();
                        $subject->setName($subjectName);
                        $this->subjectService->save($subject);
                    }
                }

                $lesson = new Lesson();
                $lesson->setStartDate($startDate);
                $lesson->setEndDate($endDate);
                $lesson->setHours($hours);
                $lesson->setSubjectId($subject);
                $lesson->setWorkerId($teacher);
                $lesson->setLessonStatus($lessonStatus);
                if ($teacherCover !== null) {
                    $lesson->setWorkerCoverId($teacherCover);
                }
                if ($room !== null) {
                    $lesson->setRoomId($room);
                }
                if ($lessonForm !== null) {
                    $lesson->setLessonForm($lessonForm);
                }
                if ($group !== null) {
                    $lesson->setGroupId($group);
                }
                $objects[] = $lesson;
            }
            $this->lessonService->saveNewLessons($objects);

            $data = null;
            $processedData = null;
            $objects = null;

            $this->output->writeln('<info>Data successfully fetched and saved ' . $teacherString
                . ' schedule data.</info>');
            $this->output->writeln('<info>Number of lessons: ' . $dataCount . '</info>');
            gc_collect_cycles();
        }
    }

    private function mapLessonForm(string $lessonForm): LessonForms
    {
        switch ($lessonForm) {
            case 'laboratorium':
                return LessonForms::Laboratory;
            case 'seminarium':
                return LessonForms::Seminar;
            case 'projekt':
                return LessonForms::Project;
            case 'zaliczenie':
                return LessonForms::Pass;
            case 'zaliczenie zdalne':
                return LessonForms::RemotePass;
            case 'zajęcia zdalne':
                return LessonForms::Remote;
            case 'wykład':
                return LessonForms::Lecture;
            case 'audytoryjne':
                return LessonForms::Auditory;
            case 'terenowe':
                return LessonForms::Field;
            case 'seminarium dyplomowe':
                return LessonForms::DiplomaSeminar;
            case 'lektorat':
                return LessonForms::LanguageCourse;
            case 'konwersatorium':
                return LessonForms::Conservatory;
            default:
                $this->output->writeln('<error>Unknown lesson form: ' . $lessonForm . '</error>');
                return LessonForms::Lecture;
        }
    }

    private function mapLessonStatus(string $lessonStatus): LessonStatuses
    {
        switch ($lessonStatus) {
            case 'normalne':
                return LessonStatuses::Normal;
            case 'odwołane':
                return LessonStatuses::Cancelled;
            case 'konsultacje':
                return LessonStatuses::Consultation;
            case 'wyjątek':
                return LessonStatuses::Exception;
            default:
                $this->output->writeln('<error>Unknown lesson status: ' . $lessonStatus . '</error>');
                return LessonStatuses::Normal;
        }
    }

    private function processData(string $jsonContent): string
    {
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Invalid JSON content.");
        }

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function processJsonData(string $jsonContent): array
    {
        $jsonContent = $this->removeFirstEmptyArrayComma($jsonContent);
        $jsonContent = $this->removeFirstEmptyArray($jsonContent);
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Invalid JSON content.");
        }

        return $data;
    }

    function removeFirstEmptyArrayComma(string $input): string
    {
        return preg_replace('/\[],/', '', $input, 1);
    }

    function removeFirstEmptyArray(string $input): string
    {
        return preg_replace('/\[]/', '', $input, 1);
    }

    function capitalizeFirstLetterOfEachWord(string $input): string
    {
        return ucwords($input);
    }
}
