<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateCourierStatuses extends Command
{
    protected $signature = 'orders:update-statuses';
    protected $description = 'Update order statuses from courier services based on tracking code';

    public function handle()
    {
        $orders = Order::whereIn('status', ['shipped', 'courier_delivered', 'in_review'])->whereNotNull('tracking_code')->get();

        foreach ($orders as $order) {
            try {
                $courier = $order->courierService;
                if (!$courier) continue;

                $url = $courier->base_url . '/status_by_trackingcode/' . $order->tracking_code;

                $response = Http::withHeaders([
                    'Api-Key' => $courier->api_key,
                    'Secret-Key' => $courier->secret_key,
                ])->get($url);

                if (!$response->successful()) {
                    Log::warning("Failed to fetch courier status for order {$order->id}: " . $response->body());
                    continue;
                }

                $data = $response->json();
                $courierStatus = $data['delivery_status'] ?? null;

                if (!$courierStatus) continue;

                $statusMap = [
                    'delivered_approval_pending' => 'courier_delivered',
                    'delivered' => 'delivered',
                    'cancelled' => 'cancelled',
                    'in_review' => 'in_review',
                    'unknown' => 'unknown',
                ];

                if (isset($statusMap[$courierStatus]) && $statusMap[$courierStatus] !== $order->status) {
                    $oldStatus = $order->status;
                    $order->status = $statusMap[$courierStatus];
                    $order->save();

                    Log::info("Order {$order->id} status updated from {$oldStatus} to {$order->status} via courier");
                }

            } catch (\Exception $e) {
                Log::error("Courier status update failed for order {$order->id}: " . $e->getMessage());
            }
        }

        $this->info('Courier statuses updated.');
    }
}
