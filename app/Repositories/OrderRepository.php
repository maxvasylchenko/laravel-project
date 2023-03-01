<?php

namespace App\Repositories;

use App\Helpers\TransactionAdapter;
use App\Models\Order;
use App\Models\OrderStatus;
use Gloudemans\Shoppingcart\Facades\Cart;

class OrderRepository implements Contracts\OrderRepositoryContract
{
    const ORDER_STATUSES = [
        'completed' => 'COMPLETED'
    ];

    public function create(array $request): Order|bool
    {
//        $user = auth()->user();
        $status = OrderStatus::default()->first();
//        dd($status, $request);
        $request = array_merge($request, ['status_id' => $status->id]);
        $order = auth()->user()->orders()->create($request);

        $this->addProductsToOrder($order);

        return $order;
//        $order = auth()->user()->orders()->create($request);
//        return auth()->user()->orders()->create($request);
//        Cart::instance('cart')->content()->groupBy('id')->each(function ($item) {
//            $cartItem = $item->first();
//            dd($cartItem);
//            dd($cartItem, $cartItem->model->endPrice);
//            dd($item);
//        });
    }

    public function setTransaction(string $vendorOrderId, TransactionAdapter $adapter): Order
    {
        $order = Order::where('vendor_order_id', $vendorOrderId)->firstOrFail();
        $order->transaction()->create((array)$adapter);

        if ($adapter->status === self::ORDER_STATUSES['completed']) {
            $order->update([
                'status_id' => OrderStatus::paid()->firstOrFail()?->id
            ]);
        }

        return $order;
    }

    protected function addProductsToOrder(Order $order)
    {
        Cart::instance('cart')->content()->groupBy('id')->each(function ($item) use ($order) {
            $cartItem = $item->first();
            $order->products()->attach($cartItem->model, [
                    'quantity' => $cartItem->qty,
                    'single_price' => $cartItem->price
            ]);

            $quantity = $cartItem->model->quantity - $cartItem->qty;

            if (! $cartItem->model->update(compact('quantity'))) {
                throw new \Exception('Smth went wrong with product (id: {$cartItem->model->id}) quantity update');
            }
        });
    }
}
