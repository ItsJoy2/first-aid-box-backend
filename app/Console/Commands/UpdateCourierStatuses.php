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
            Log::info("Processing order {$order->id} with tracking: {$order->tracking_code}");

            $courier = $order->courierService;
            if (!$courier) {
                Log::warning("Order {$order->id} skipped: No courier service.");
                continue;
            }

            $url = $courier->base_url . '/status_by_trackingcode/' . $order->tracking_code;

            $response = Http::withHeaders([
                'Api-Key' => $courier->api_key,
                'Secret-Key' => $courier->secret_key,
            ])->get($url);

            if (!$response->successful()) {
                Log::warning("Failed to fetch status for order {$order->id}: " . $response->body());
                continue;
            }

            $data = $response->json();
            Log::info("API Response for order {$order->id}: " . json_encode($data));

            $courierStatus = $data['delivery_status'] ?? null;
            if (!$courierStatus) {
                Log::warning("No delivery_status in response for order {$order->id}");
                continue;
            }

            $statusMap = [
                'delivered_approval_pending' => 'courier_delivered',
                'delivered' => 'delivered',
                'cancelled' => 'cancelled',
                'in_review' => 'in_review',
                'unknown' => 'unknown',
            ];

            $newStatus = $statusMap[$courierStatus] ?? null;

            if (!$newStatus) {
                Log::warning("Unmapped status `{$courierStatus}` for order {$order->id}");
                continue;
            }

            if ($newStatus === $order->status) {
                Log::info("Order {$order->id} already in status: {$order->status}");
                continue;
            }

            $oldStatus = $order->status;
            $order->status = $newStatus;
            $order->save();

            Log::info("Order {$order->id} status changed from {$oldStatus} to {$newStatus}");

        } catch (\Exception $e) {
            Log::error("Error updating order {$order->id}: " . $e->getMessage());
        }
    }

        $this->info('Courier statuses updated.');
    }
}
