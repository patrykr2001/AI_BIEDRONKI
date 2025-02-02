<?php

declare(strict_types=1);

namespace App\Controller;
use App\Repository\SubjectRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiSubjectController extends AbstractController
{
    private SubjectRepository $subjectRepository;

    public function __construct(SubjectRepository $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }
    #[Route('/api/subjects/{name}', methods: ['GET'])]
    public function getApiSubjects(string $name): JsonResponse
    {
        if (strlen($name) < 3) {
            return new JsonResponse([], Response::HTTP_OK);
        }

        $S_name= "%".$name;
        $S_name= $name."%";

        $data =  $this->subjectRepository->findSubjectByName($S_name);

        $data=json_encode($data);
        // Filter data based on whether the name contains the provided string
        //$filteredData = array_filter($data, fn($subject) => stripos($subject['name'], $name) !== false);

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
