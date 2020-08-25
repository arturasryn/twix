<?php

namespace App\Controller\v1;

use App\Controller\BaseController;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Requests\Order\DeleteOrderRequest;
use App\Requests\Order\CreateOrderRequest;
use App\Requests\Order\FindOrderRequest;
use App\Requests\Order\UpdateOrderRequest;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Class OrderController
 * @package App\Controller
 */
class OrderController extends BaseController
{
    private $userRepository;
    private $orderRepository;

    public function __construct(UserRepository $userRepository, OrderRepository $orderRepository)
    {
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/orders", name="orders", methods={"GET"})
     */
    public function all(Request $request){
        $query = $this->orderRepository->query();
        $paginationService = new PaginationService();
        $paginator = $paginationService->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit',2)
        );

        return $this->response([
            'orders' => $paginator->getIterator(),
            'total' => $paginationService->total($paginator),
            'pages' => $paginationService->lastPage($paginator)
        ]);
    }

    /**
     * @param CreateOrderRequest $request
     * @return JsonResponse
     * @Route("/orders", name="orders_create", methods={"POST"})
     */
    public function create(CreateOrderRequest $request){
        $user = $this->userRepository->find($request->get('user_id'));
        $order = $this->orderRepository->create($user, $request->all());
        return $this->response([
            'message' => 'Order successfully created.',
            'data' => [
                'order_id' => $order->getId(),
                  //@todo Think about relation links in JSON responses for team comfort :P
//                'user_orders_count' => $user->getOrdersCount(),
//                'user_url' => $this->generateUrl('users_get', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
            ]
        ]);
    }

    /**
     * @param $id
     * @param FindOrderRequest $request
     * @return JsonResponse
     * @Route("/orders/{id}", name="orders_get", methods={"GET"})
     */
    public function find($id, FindOrderRequest $request){
        $order = $this->orderRepository->find($id);
        return $this->response($order->toArray());
    }

    /**
     * @param UpdateOrderRequest $request
     * @return JsonResponse
     * @Route("/orders/{id}", name="orders_update", methods={"PUT"})
     */
    public function update(UpdateOrderRequest $request){
        $this->orderRepository->save($request->all());

        return $this->response([
            'message' => 'Order successfully updated.'
        ]);
    }

    /**
     * @param DeleteOrderRequest $request
     * @return JsonResponse
     * @Route("/orders/{id}", name="orders_delete", methods={"DELETE"})
     */
    public function delete(DeleteOrderRequest $request) {
        $this->orderRepository->delete($request->get('id'));
        return $this->response([
            'message' => 'Order successfully deleted.'
        ]);
    }

}