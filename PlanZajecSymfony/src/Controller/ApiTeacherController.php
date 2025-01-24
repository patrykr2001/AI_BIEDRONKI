<?php

declare(strict_types=1);

namespace App\Controller;
use App\Repository\TeacherRepository;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiTeacherController extends AbstractController
{
    private TeacherRepository $teacherRepository;

    public function __construct(TeacherRepository $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }
    #[Route('/api/teachers/{name}', methods: ['GET'])]
    public function getApiTeachers(string $name): JsonResponse
    {
        if (strlen($name) < 3) {
            return new JsonResponse([], Response::HTTP_OK);
        }

        $S_name= "%".$name;
        $S_name= $name."%";

        $data =  $this->teacherRepository->findTeacherByName($S_name);

        $data=json_encode($data);

        // Filter data based on whether the name contains the provided string
        //$filteredData = array_filter($data, fn($teacher) => stripos($teacher['name'], $name) !== false);

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
