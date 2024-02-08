<?php 
namespace App\Tests;

use App\Controller\OrdersController;
use App\Handler\OrderHandler;
use App\Shared\Dto\OrderDto;
use App\Shared\Factory;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class OrderTest extends TestCase
{
    public function testIndex()
    {
        // Create a mock EntityManagerInterface
        $entityManager = $this->createMock(EntityManagerInterface::class);

        // Create a mock OrderHandler
        $orderHandler = $this->createMock(OrderHandler::class);

        // Create a mock Request
        $request = $this->createMock(Request::class);

        // Create a mock response data
        $responseData = [
            // your mock response data here
        ];

        // Set up expectations for the OrderHandler mock
        $orderHandler->expects($this->once())
            ->method('processList')
            ->with($request)
            ->willReturn($responseData);

        // Create a mock Serializer
        $serializer = $this->createMock(SerializerInterface::class);

        // Set up expectations for the Serializer mock
        $serializer->expects($this->once())
            ->method('serialize')
            ->with($responseData, 'json')
            ->willReturn(json_encode($responseData));

        // Create a mock OrdersController with the mock dependencies
        $controller = $this->createMock(OrdersController::class);
            

        // Set up expectations for the json method mock
        $controller->expects($this->once())
            ->method('json')
            ->with(json_encode($responseData))
            ->willReturn(new JsonResponse(json_encode($responseData)));

        // Call the index method with the mock Request
        $response = $controller->index($request);

        // Assert that the response is an instance of JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testCreateOrderSuccess()
    {
        // Mock the Request object
        $requestData = [
            'delivery_address' => '123 Main St',
            'delivery_option' => 'Express',
            'orderitem' => [
                ['item' => 'Product A', 'quantity' => 2],
                ['item' => 'Product B', 'quantity' => 1],
            ]
        ];
        // Create a mock OrderHandler
        $orderHandler = $this->createMock(OrderHandler::class);

        // Create a mock EntityManagerInterface
        $entityManager = $this->createMock(EntityManagerInterface::class);

        // Create a mock Request
        $request = $this->createMock(Request::class);

        $request->method('getContent')
            ->willReturn(json_encode($requestData));

        // Mock the serializer
        $serializer = $this->getMockBuilder(Serializer::class)
            ->getMock();
        $serializer->method('deserialize')
            ->willReturn(new OrderDto($requestData['delivery_address'],
            $requestData['delivery_option'],
            $requestData['orderitem'],
            $requestData['orderitem'])); // Stub the OrderDto object


        // Create an instance of the controller
        $orderController = new OrdersController( $entityManager, $orderHandler);

        // Call the function to test
        $response = $orderController->createOrder($request);

        // Assertions
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
    }
}