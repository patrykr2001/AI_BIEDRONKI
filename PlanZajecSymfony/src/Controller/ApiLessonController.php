<?php

declare(strict_types=1);

namespace App\Controller;
use App\Repository\LessonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiLessonController extends AbstractController
{
    #[Route('/api/lessons', methods: ['GET'])]
    public function getApiLessons(string $teacher , string $subject , string $group , string $name ): JsonResponse
    {

        $c = new LessonRepository();

        $data = $c->findAllLessons($name);

        return new JsonResponse($data, Response::HTTP_OK);
    }


}
