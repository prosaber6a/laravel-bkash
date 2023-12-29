<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\Order::class;

    public function definition(): array
    {
        $products = ['Mobile', 'Laptop', 'Tablet', 'Desktop', 'TV', 'Camera', 'Printer', 'Scanner', 'Projector', 'UPS'];
        static $invoice = 20;
        return [
            'product_name' => $products[rand(0, 9)],
            'currency' => 'BDT',
            'amount' => rand(500, 10000),
            'invoice' => $invoice++,
            'status' => 'Pending',
        ];
    }
}
