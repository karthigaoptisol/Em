<?php 
namespace App\Handler;

use App\Entity\OrderItems;
use App\Entity\Orders;
use App\Repository\OrdersRepository;
use App\Shared\Factory;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class OrderHandler
{

	private EntityManagerInterface $em;
    public function __construct(
		EntityManagerInterface $em
	)
	{
		$this->em = $em;
	}

    public function processList(Request $request): array
	{
		$result = $this->em->getRepository(Orders::class)->list($request->query->all());
        $orders = [];
        foreach($result as $order) {
            $orders[] = Factory::OrderDtoInstance($order);
        }

        return $orders;
	}

	public function processCreate(Request $request): JsonResponse
	{
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

	public function processUpdate(Request $request) : JsonResponse {
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