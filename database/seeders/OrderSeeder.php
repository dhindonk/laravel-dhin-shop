<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Factories\OrderFactory;
use Database\Factories\OrderItemFactory;
use App\Models\Order;
use App\Models\OrderItem;


class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Loop through each month in the past year
        $startDate = now()->subYear()->startOfMonth();
        $endDate = now()->startOfMonth();

        while ($startDate < $endDate) {
            // Create a random number of orders for each month
            $numberOfOrders = rand(5, 15);

            for ($i = 0; $i < $numberOfOrders; $i++) {
                // Create order and associated order items
                $order = Order::factory()->create([
                    'created_at' => $startDate->copy()->addDays(rand(0, 29)),
                ]);

                // Generate random number of order items for each order
                $numberOfItems = rand(1, 5);
                OrderItem::factory()->count($numberOfItems)->create([
                    'order_id' => $order->id,
                ]);
            }

            // Move to the next month
            $startDate->addMonth();
        }
    }
}
