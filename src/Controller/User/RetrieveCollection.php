<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RetrieveCollection
{
    private SerializerInterface $serializer;
    private UserRepository $repository;

    public function __construct(SerializerInterface $serializer, UserRepository $repository)
    {
        $this->serializer = $serializer;
        $this->repository = $repository;
    }

    /**
     * @Route(path="/api/users", methods={"GET"}, name="user_index")
     *
     * @SWG\Get(
     *   tags={"Users"},
     *
     * @SWG\Parameter(
     *    name="limit",
     *    in="query",
     *    type="integer",
     *    default="10",
     *    description="Enter the number of result items to show on a page",
     *    required=true
     *   ),
     * @SWG\Parameter(
     *    name="page",
     *    in="query",
     *    type="integer",
     *    default="1",
     *    description="Enter the number of result page to show",
     *    required=true
     *   ),
     * @SWG\Parameter(
     *    name="sort_criteria",
     *    in="query",
     *    type="string",
     *    default="id",
     *    description="Enter the order criteria (field name)",
     *    required=false
     *   ),
     * @SWG\Parameter(
     *    name="sort_type",
     *    in="query",
     *    type="string",
     *    default="ASC",
     *    description="Enter ASC for ascending or DESC for descending order",
     *    required=false
     *   )
     * )
     *
     * @SWG\Response(
     *   response=200,
     *   description="List of users",
     *   @SWG\Schema(
     *     @Model(type=User::class, groups={"user:index"})
     *   )
     * )
     */
    public function __invoke(Request $request): JsonResponse
    {
        $limit = $request->query->get('limit', 10);
        $page = $request->query->get('page', 1);
        $offset = ($page - 1) * $limit;
        $orderBy = $request->query->get('sort_criteria', 'id');
        $direction = $request->query->get('sort_type', 'ASC');

        $users = $this->repository->findBy([], [$orderBy => $direction], $limit, $offset);
        $data = $this->serializer->serialize($users, 'json', ['groups' => ['user:index']]);

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }
}
