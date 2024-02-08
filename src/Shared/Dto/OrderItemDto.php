<?php
namespace App\Shared\Dto;

class OrderItemDto
{
    public function __construct(
        public ?string $id = null,
        public ?int $quantity = null,
        public ?string $item
    )
    {
        

    }
}