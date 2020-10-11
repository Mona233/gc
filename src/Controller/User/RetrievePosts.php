<?php

namespace App\Controller\User;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RetrievePosts
{
    private SerializerInterface $serializer;
    private PostRepository $repository;

    public function __construct(SerializerInterface $serializer, PostRepository $repository)
    {
        $this->serializer = $serializer;
        $this->repository = $repository;
    }

    /**
     * @Route(path="/api/users/{user}/posts", methods={"GET"}, name="users_get_posts")
     *
     * @SWG\Get(
     *     tags={"Users"}
     * )
     *
     * @SWG\Parameter(
     *    name="user",
     *    in="path",
     *    type="integer",
     *    description="User ID",
     * )
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
     * @SWG\Response(
     *     response=200,
     *     description="List of user posts",
     *     @SWG\Schema(
     *       @Model(type=Post::class, groups={"post:index"})
     *   )
     * )
     */
    public function __invoke(User $user, Request $request): JsonResponse
    {
        $limit = $request->query->get('limit', 10);
        $page = $request->query->get('page', 1);
        $offset = ($page - 1) * $limit;
        $orderBy = $request->query->get('sort_criteria', 'id');
        $direction = $request->query->get('sort_type', 'ASC');

        $posts = $this->repository->findBy(['user' => $user], [$orderBy => $direction], $limit, $offset);
        $data = $this->serializer->serialize($posts, 'json', ['groups' => ['post:index', 'post:user', 'user:index']]);

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }
}
