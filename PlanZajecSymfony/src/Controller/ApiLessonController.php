<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiLessonController extends AbstractController
{
    #[Route('/api/lessons', methods: ['GET'])]
    public function getApiLessons(): JsonResponse
    {
        $data = [
            ['id' => 1, 'name' => 'Lessons 1'],
            ['id' => 2, 'name' => 'Lessons 2'],
            ['id' => 3, 'name' => 'Lessons 3'],
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
