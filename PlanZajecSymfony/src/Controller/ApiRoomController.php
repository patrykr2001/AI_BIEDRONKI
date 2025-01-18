<?php

declare(strict_types=1);

namespace App\Controller;

use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiRoomController extends AbstractController
{
    #[Route('/api/rooms/{name}', methods: ['GET'])]
    public function getApiRooms(string $name): JsonResponse
    {
        if (strlen($name) < 3) {
            return new JsonResponse([], Response::HTTP_OK);
        }

        $data = [
            ['id' => 1, 'name' => 'Room 1'],
            ['id' => 2, 'name' => 'Room 2'],
            ['id' => 3, 'name' => 'Room 3'],
        ];

        // Filter data based on whether the name contains the provided string
        $filteredData = array_filter($data, fn($room) => stripos($room['name'], $name) !== false);

        return new JsonResponse($filteredData, Response::HTTP_OK);
    }
}
