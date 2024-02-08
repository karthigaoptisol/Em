<?php

namespace App\Controller;

// use App\Entity\OrderItems;

use App\Entity\OrderItems;
use App\Entity\Orders;
use App\Handler\OrderHandler;
use App\Repository\OrdersRepository;
use App\Shared\Dto\OrderDto;
use App\Shared\Dto\OrderItemDto;
use App\Shared\Factory;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\SerializerInterface;
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
        schema: new OA\Schema(type: "object",ref: new Model(type: OrderItemDto::class))
    )]
    #[OA\Tag(name: 'Orders Create')]
    public function createOrder(Request $request): JsonResponse
    {
        // Parse request payload
        $requestData = (array)$request->query->all();
        if(empty($requestData)) {
            $requestData = $request->getPayload()->all();
        }
        $estimatedDate = new DateTime('tomorrow');
        // Create new order entity
        $order = new Orders();
        $order
            ->setDeliveryAddress($requestData['delivery_address'])
            ->setDeliveryOption($requestData['delivery_option'])
            ->setEstimatedDeliveryDate($estimatedDate)
            ->setStatus("processing")
            ->setCreatedAt(new \DateTime());
        
        
        $this->em->persist($order);

        $orderItemsData = $requestData['orderitem'];
        foreach($orderItemsData as $orderData) {
            $orderItem = new OrderItems();
            $orderItem->setItem($orderData['item'])
            ->setQuantity($orderData['quantity']);
            $order->addOrderitem($orderItem);
            
            $this->em->persist($orderItem);
        }
        $this->em->flush();
        
        return new JsonResponse(['message' => 'Order created successfully'], JsonResponse::HTTP_CREATED);

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
        $data = $request->query->all();
        if(empty($data)) {
            $data = $request->getPayload()->all();
        }
        $order = $this->em->getRepository(Orders::class)->findOneBy(['id' => $data['order_id']]);
        $order->setStatus($data['status']);
        $this->em->persist($order);
        $this->em->flush();
        return new JsonResponse(['message' => 'Order updated successfully'], JsonResponse::HTTP_OK);
    }
}
