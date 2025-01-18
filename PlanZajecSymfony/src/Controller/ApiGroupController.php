<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiGroupController extends AbstractController
{
    #[Route('/api/groups/{name}', methods: ['GET'])]
    public function getApiGroups(string $name): JsonResponse
    {
        if (strlen($name) < 3) {
            return new JsonResponse([], Response::HTTP_OK);
        }

        $data = [
            ['id' => 1, 'name' => 'Group 1'],
            ['id' => 2, 'name' => 'Group 2'],
            ['id' => 3, 'name' => 'Group 3'],
        ];

        // Filter data based on whether the name contains the provided string
        $filteredData = array_filter($data, fn($group) => stripos($group['name'], $name) !== false);

        return new JsonResponse($filteredData, Response::HTTP_OK);
    }
}
