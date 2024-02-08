<?php
namespace App\Shared\Dto;

/**
 * @SWG\Definition()
 */
class OrderDto
{
    public function __construct(
        public ?string $id = null,
        public ?string $deliveryOption,
        public ?string $deliveryAddress,
        public ?string $status,
        public ?array $items = []
    )
    {
        

    }
}