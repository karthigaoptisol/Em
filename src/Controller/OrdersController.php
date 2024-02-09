<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Handler\OrderHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use Throwable;

#[Route('/api/orders')]
class OrdersController extends AbstractController
{
    private EntityManagerInterface $em;
    private OrderHandler $orderHandler;
    public function __construct(
        EntityManagerInterface $em,
        OrderHandler $orderHandler,
    ) {
        $this->em = $em;
        $this->orderHandler = $orderHandler;

    }


    #[Route('', name: 'app_order', methods:['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Orders::class, groups: ['full']))
        )
    )]
    #[OA\Parameter(
        name: 'order_id',
        in: 'query',
        description: 'Order Id ',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'status',
        in: 'query',
        description: 'Status',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'Orders')]
    public function index(Request $request): JsonResponse
	{
		try {
            $orders = $this->orderHandler->processList($request);
            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            $orderListJson = $serializer->serialize($orders, 'json');

            return $this->json($orderListJson);
		} catch (Throwable $thr) {
			return $thr->getMessage();
		}
	}

    #[Route("", name: 'create_order', methods:['POST'] )]
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Orders::class, groups: ['full']))
        )
    )]
    #[OA\Parameter(
        name: 'delivery_option',
        in: 'query',
        description: 'Delivery Option ',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'delivery_address',
        in: 'query',
        description: 'Delivery Address',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'orderitem',
        in: 'query',
        description: 'Order Items',
        schema: new OA\Schema(type: 'array',
        items: new OA\Items(
            // your list item
                type: 'object',
                properties: [
                    new OA\Property(
                        property: "item",
                        type: "string",
                        example: "item name"
                    ),
                    new OA\Property(
                        property: "quantity",
                        type: "int",
                        example: "2"
                    )
                ]
            )
    ))]
    #[OA\Tag(name: 'Orders Create')]
    public function createOrder(Request $request): JsonResponse
    {
        try {
            return $this->orderHandler->processCreate($request);
		} catch (Throwable $thr) {
			return  new JsonResponse(['error'=> $thr->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
		}
    }

    #[Route("", name: 'update_order', methods:['PATCH'] )]
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Orders::class, groups: ['full']))
        )
    )]
    #[OA\Parameter(
        name: 'order_id',
        in: 'query',
        description: 'Order Id ',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'status',
        in: 'query',
        description: 'Status',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'Orders Update')]
    public function update(Request $request): JsonResponse
    {
        try {
            return $this->orderHandler->processUpdate($request);
		} catch (Throwable $thr) {
			return $thr->getMessage();
		}
    }
}
