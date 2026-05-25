<?php

namespace App\Cart;

class ApiCart implements CartInterface
{
    public function add(int $productId, int $quantity): void
    {
        dd([
            'action'    => 'API - add to cart',
            'productId' => $productId,
            'quantity'  => $quantity,
        ]);
    }

    public function remove(int $productId): void
    {
        dd([
            'action'    => 'API - remove from cart',
            'productId' => $productId,
        ]);
    }

    public function getItems(): array
    {
        dd(['action' => 'API - get cart items']);
    }

    public function clear(): void
    {
        dd(['action' => 'API - clear cart']);
    }
}