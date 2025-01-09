<?php

namespace App\Service;

use App\Config\ConfigReader;
use App\Entity\DataUpdateLog;
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
use DateTime;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ZutDataUpdater{
    private HttpClientInterface $client;
    private ZutUrlBuilder $urlBuilder;
    private OutputInterface $output;
    private RoomService $roomService;
    private TeacherService $teacherService;
    private SubjectService $subjectService;
    private DataUpdateLogService $dataUpdateLogService;
    private LessonService $lessonService;

    public function __construct(HttpClientInterface $client, RoomService $roomService, TeacherService $teacherService,
                                SubjectService $subjectService, DataUpdateLogService $dataUpdateLogService,
                                LessonService  $lessonService)
    {
        $url = (new ConfigReader())->getApiBaseUrl();
        $this->client = $client;
        $this->urlBuilder = new ZutUrlBuilder($url);
        $this->roomService = $roomService;
        $this->teacherService = $teacherService;
        $this->subjectService = $subjectService;
        $this->dataUpdateLogService = $dataUpdateLogService;
        $this->lessonService = $lessonService;
    }

    public function updateOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    public function updateZutData(): void{
        $lastMontlyDataUpdate = $this->dataUpdateLogService->findLastByType(DataUpdateTypes::Monthly);
        if ($lastMontlyDataUpdate === null) {
            $lastMontlyDataUpdate = new DataUpdateLog();
            $lastMontlyDataUpdate->setType(DataUpdateTypes::Monthly);
            $lastMontlyDataUpdate->setUpdateDate(DateHelper::getCurrentDay());
            $this->dataUpdateLogService->save($lastMontlyDataUpdate);
        }

        if ($lastMontlyDataUpdate !== null) {
            $lastUpdateDate = $lastMontlyDataUpdate->getUpdateDate();
            $currentDate = DateHelper::getCurrentDay();
            $diff = $currentDate->diff($lastUpdateDate);
            if ($diff->days < 30) {
                $this->output->writeln('<info>There is no need to update monthly data.</info>');
            } else {
                $this->output->writeln('<info>Updating monthly data...</info>');
                $this->updateSpecificZutData(ZutDataKinds::Teachers);
//                  $this->updateSpecificZutData(ZutDataKinds::Groups);
                $this->updateSpecificZutData(ZutDataKinds::Subjects);
                $this->updateSpecificZutData(ZutDataKinds::Rooms);
            }
            $lastMontlyDataUpdate = new DataUpdateLog();
            $lastMontlyDataUpdate->setType(DataUpdateTypes::Monthly);
            $lastMontlyDataUpdate->setUpdateDate(DateHelper::getCurrentDay());
            $this->dataUpdateLogService->save($lastMontlyDataUpdate);
        }

        $lastWeeklyDataUpdate = $this->dataUpdateLogService->findLastByType(DataUpdateTypes::Weekly);
        if ($lastWeeklyDataUpdate === null) {
            $lastWeeklyDataUpdate = new DataUpdateLog();
            $lastWeeklyDataUpdate->setType(DataUpdateTypes::Weekly);
            $lastWeeklyDataUpdate->setUpdateDate(DateHelper::getCurrentWeek()[0]);
            $this->dataUpdateLogService->save($lastWeeklyDataUpdate);
        }
        if ($lastWeeklyDataUpdate !== null) {
            $lastUpdateDate = $lastWeeklyDataUpdate->getUpdateDate();
            $currentDate = DateHelper::getCurrentWeek()[0];
            $diff = $currentDate->diff($lastUpdateDate);
            if ($diff->days < 7) {
                $this->output->writeln('<info>There is no need to update weekly data.</info>');
            } else {
                $this->output->writeln('<info>Updating weekly data...</info>');
                $this->updateTeachersScheduleData();
            }
            $lastWeeklyDataUpdate = new DataUpdateLog();
            $lastWeeklyDataUpdate->setType(DataUpdateTypes::Weekly);
            $lastWeeklyDataUpdate->setUpdateDate(DateHelper::getCurrentWeek()[0]);
            $this->dataUpdateLogService->save($lastWeeklyDataUpdate);
        }
    }

    public function updateTeachersScheduleData(): void{
        $teachers = array_map(fn($teacher) => $teacher->getName(), $this->teacherService->getAllTeachers());
        $dates = (new ConfigReader())->getDateRange();
        $this->updateSpecificTeachersScheduleData($teachers, new DateTime($dates['start']), new DateTime($dates['end']));
    }

    private function updateSpecificZutData(ZutDataKinds $kind): void
    {
        $this->output->writeln('<info>Fetching ' . $kind->name . ' data from API...</info>');
        $response = $this->client->request('GET', $this->urlBuilder->buildDataUrl($kind, ''), [
            'headers' => [
                'Accept-Charset' => 'UTF-8',
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            $this->output->writeln('<error>Failed to fetch '.$kind->name.' data from API.</error>');
            return;
        }
        $this->output->writeln('<info>Successfully fetched ' . $kind->name . ' data from API.</info>');

        $data = $response->getContent();

//        $processedData = $this->processData($data);
//        file_put_contents($kind->name.'.json', $processedData);

        $this->output->writeln('<info>Processing ' . $kind->name . ' data...</info>');

        $processedData = $this->processJsonData($data);

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

        $this->output->writeln('<info>Data successfully fetched and saved '.$kind->name.' data.</info>');
    }

    function getSubstringBeforeParenthesis(string $input): string
    {
        $position = strpos($input, '(');
        if ($position === false) {
            return trim($input);
        }
        return trim(substr($input, 0, $position));
    }

    private function updateSpecificTeachersScheduleData(array $teachers, DateTime $start, DateTime $end): void
    {
        foreach ($teachers as $teacherString) {
            $data = [ZutScheduleDataKinds::Teachers->value => $teacherString];

            $this->output->writeln('<info>Fetching ' . $teacherString . ' schedule data from API...</info>');

            $response = $this->client->request('GET', $this->urlBuilder
                ->buildScheduleUrl($data, $start, $end), [
                'headers' => [
                    'Accept-Charset' => 'UTF-8',
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                $this->output->writeln('<error>Failed to fetch ' . $teacherString . ' schedule data from API.</error>');
                continue;
            }
            $this->output->writeln('<info>Successfully fetched ' . $teacherString . ' schedule data from API.</info>');

            $data = $response->getContent();
//            $processedData = $this->processData($data);
//            file_put_contents($teacher.'.json', $processedData);
            $this->output->writeln('<info>Processing ' . $teacherString . ' schedule data...</info>');

            $processedData = $this->processJsonData($data);

            $this->output->writeln('<info>Saving ' . $teacherString . ' schedule data...</info>');

            $objects = [];

            foreach ($processedData as $item) {
                $room = $this->roomService->getRoomByName($item['room']);
                $subject = $this->subjectService->getSubjectByName($item['subject']);
                $teacher = $this->teacherService->getTeacherByName($item['worker']);
                $lessonForm = $this->mapLessonForm($item['lesson_form']);
                $lessonStatus = $this->mapLessonStatus($item['status_item']);
                $hours = $item['hours'];
                $startDate = new DateTime($item['start_date']);
                $endDate = new DateTime($item['end_date']);

                $teacherCover = null;
                if ($item['worker_cover'] !== null && $item['worker_cover'] !== '') {
                    $teacherCover = $this->teacherService->getTeacherByName($item['worker_cover']);
                }

                $lesson = new Lesson();
                $lesson->setStartDate($startDate);
                $lesson->setEndDate($endDate);
                $lesson->setHours($hours);
                $lesson->setRoomId($room->getId());
                $lesson->setSubjectId($subject->getId());
                $lesson->setWorkerId($teacher->getId());
                $lesson->setLessonForm($lessonForm);
                $lesson->setLessonStatus($lessonStatus);
                if ($teacherCover !== null) {
                    $lesson->setWorkerCoverId($teacherCover->getId());
                }
                $objects[] = $lesson;
            }
            $this->lessonService->saveNewLessons($objects);

            $this->output->writeln('<info>Data successfully fetched and saved ' . $teacherString . ' schedule data.</info>');
        }
    }

    private function mapLessonForm(string $lessonForm): LessonForms
    {
        return match ($lessonForm) {
            'laboratorium' => LessonForms::Laboratory,
            'seminarium' => LessonForms::Seminar,
            'projekt' => LessonForms::Project,
            'zaliczenie' => LessonForms::Pass,
            'zaliczenie zdalne' => LessonForms::RemotePass,
            'zajęcia zdalne' => LessonForms::Remote,
            default => LessonForms::Lecture,
        };
    }

    private function mapLessonStatus(string $lessonStatus): LessonStatuses
    {
        return match ($lessonStatus) {
            'odwołane' => LessonStatuses::Cancelled,
            default => LessonStatuses::Normal,
        };
    }

    private function processData(string $jsonContent): string
    {
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Invalid JSON content.");
        }

        $formattedJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return $formattedJson;
    }

    private function processJsonData(string $jsonContent): array
    {
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Invalid JSON content.");
        }

        return $data;
    }
}
