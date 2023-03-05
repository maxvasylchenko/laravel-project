<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Facades\Invoice as InvoiceBuilder;

class InvoicesService implements Contracts\InvoicesServiceContract
{

    public function generate(Order $order): Invoice
    {
//        $user = $order->user;
        $customer = new Buyer([
            'name' => $order->fullName,
            'custom_fields' => [
                'email' => $order->email,
                'phone' => $order->phone,
                'city' => $order->city,
                'address' => $order->address
            ],
        ]);

//        dd($order->products);
//        $items = $this->getInvoiceItems($order->products);
//        dd($items);
        $invoice = InvoiceBuilder::make()
            ->series('BIG')
            ->serialNumberFormat($order->vendor_order_id)
            ->status($order->status->name)
            ->buyer($customer)
            ->taxRate(config('cart.tax'))
            ->filename($order->vendor_order_id)
            ->addItems($this->getInvoiceItems($order->products))
            ->save('public');
//        return new Invoice();
//        dd($order->in_process);
        if ($order->in_process) {
            $invoice->payUntilDays(5);
        }

        return $invoice;
    }

    protected function getInvoiceItems(Collection $products): array
    {
        $items = [];

        foreach($products as $product) {
            $items[] = (new InvoiceItem())
                ->title($product->title)
                ->pricePerUnit($product->pivot->single_price)
                ->quantity($product->pivot->quantity)
                ->units('од.');
        }

        return $items;
    }
}
