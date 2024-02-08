<?php 
    namespace App\Shared;

    use App\Entity\Orders;
use App\Entity\OrderItems;
use App\Shared\Dto\OrderDto;
use App\Shared\Dto\OrderItemDto;

    class Factory{

        public static function OrderDtoInstance(Orders $order): OrderDto
        {
            $items = [];
            if(null !== $order->getOrderitems()) {
                foreach($order->getOrderItems() as $item){
                    $items[] = self::OrderItemDtoInstance($item);
                }
            }
            return new OrderDto(
                id: $order->getId(),
                deliveryOption: $order->getDeliveryOption(),
                deliveryAddress: $order->getDeliveryAddress(),
                status: $order->getStatus(),
                items: $items
            );
        }

        public static function OrderItemDtoInstance(OrderItems $item): OrderItemDto
        {
            return new OrderItemDto(
                id: $item->getId(),
                quantity: $item->getQuantity(),
                item: $item->getItem()
            );
        }
    }
?>