<?php

declare(strict_types=1);

namespace App\Controller;
use App\Repository\GroupRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiGroupController extends AbstractController
{

    private GroupRepository $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    #[Route('/api/groups/{name}', methods: ['GET'])]
    public function getApiGroups(string $name): JsonResponse
    {

        if (strlen($name) < 3) {
            return new JsonResponse([], Response::HTTP_OK);
        }

        $S_name= "%".$name;
        $S_name= $name."%";

        $data =  $this->groupRepository->findGroupByName($S_name);

        // Filter data based on whether the name contains the provided string
        //$filteredData = array_filter($data, fn($group) => stripos($group['name'], $name) !== false);

        $data=json_encode($data);

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
