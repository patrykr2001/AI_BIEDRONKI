<?php

declare(strict_types=1);

namespace App\Controller;
use App\Repository\RoomRepository;

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

        $c = new RoomRepository();

        $data = $c->findRoomByName($name);

        $data=json_encode($data);

        // Filter data based on whether the name contains the provided string
        //$filteredData = array_filter($data, fn($room) => stripos($room['name'], $name) !== false);

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
