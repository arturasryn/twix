<?php

namespace App\Controller\v1;

use App\Controller\BaseController;
use App\Repository\UserRepository;
use App\Request\User\CreateUserRequest;
use App\Request\User\DeleteUserRequest;
use App\Request\User\FindUserRequest;
use App\Request\User\UpdateUserRequest;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends BaseController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/users", name="users", methods={"GET"})
     */
    public function all(Request $request){

        $query = $this->userRepository->query();
        $paginationService = new PaginationService();
        $paginator = $paginationService->paginate(
                $query,
                $request->query->getInt('page', 1),
                $request->query->getInt('limit',2)
        );

        return $this->response([
            'users' => $paginator->getIterator(),
            'total' => $paginationService->total($paginator),
            'pages' => $paginationService->lastPage($paginator)
        ]);
    }

    /**
     * @param CreateUserRequest $request
     * @return JsonResponse
     * @Route("/users", name="users_create", methods={"POST"})
     */
    public function create(CreateUserRequest $request){
        $user = $this->userRepository->save($request->all());

        return $this->response([
            'message' => 'User successfully created.',
            'data' => [
                'user_id' => $user->getId()
            ]
        ]);
    }


    /**
     * @param $id
     * @param FindUserRequest $request
     * @return JsonResponse
     * @Route("/users/{id}", name="users_get", methods={"GET"})
     */
    public function find($id, FindUserRequest $request){
        $user = $this->userRepository->find($id);
        return $this->response($user->toArray());
    }

    /**
     * @param UpdateUserRequest $request
     * @return JsonResponse
     * @Route("/users/{id}", name="users_update", methods={"PUT"})
     */
    public function update(UpdateUserRequest $request){
        $this->userRepository->save($request->all());

        return $this->response([
            'message' => 'User successfully updated.'
        ]);
    }

    /**
     * @param DeleteUserRequest $request
     * @return JsonResponse
     * @Route("/users/{id}", name="users_delete", methods={"DELETE"})
     */
    public function delete(DeleteUserRequest $request) {
        $this->userRepository->delete($request->get('id'));
        return $this->response([
            'message' => 'User successfully deleted.'
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/users/{id}/orders", name="user_orders", methods={"GET"})
     */
    public function orders(Request $request) {
        $orders = $this->userRepository->orders($request->get('id'));
        return $this->response($orders);
    }

}