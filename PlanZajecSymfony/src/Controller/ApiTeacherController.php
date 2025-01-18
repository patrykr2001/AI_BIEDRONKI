<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiTeacherController extends AbstractController
{
    #[Route('/api/teachers/{name}', methods: ['GET'])]
    public function getApiTeachers(string $name): JsonResponse
    {
        if (strlen($name) < 3) {
            return new JsonResponse([], Response::HTTP_OK);
        }

        $data = [
            ['id' => 1, 'name' => 'Teacher 1'],
            ['id' => 2, 'name' => 'Teacher 2'],
            ['id' => 3, 'name' => 'Teacher 3'],
        ];

        // Filter data based on whether the name contains the provided string
        $filteredData = array_filter($data, fn($teacher) => stripos($teacher['name'], $name) !== false);

        return new JsonResponse($filteredData, Response::HTTP_OK);
    }
}
