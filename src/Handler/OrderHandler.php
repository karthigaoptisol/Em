<?php 
namespace App\Handler;

use App\Entity\Orders;
use App\Repository\OrdersRepository;
use App\Shared\Factory;
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
}