<?php

namespace App\Services;

use App\DataTransferObjects\Order\StoreOrderDTO;
use App\Enums\OrderCountry;
use App\Misc\MorphMap;
use App\Models\FormSubmission;
use App\Models\Order;
use App\Models\PaymentType;
use App\Models\ShippingType;
use App\Notifications\OrderCreated;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class OrderService
{
    /**
     * @throws Exception
     */
    public function storeOrder(StoreOrderDTO $orderDTO, Collection $products): ?Order
    {
        $orderData = $orderDTO->toArray(flatten: true);
        $order = new Order($orderData);

        $groupedProductData = (clone $products)->groupBy("type");
        $groupedProducts = collect();

        $subtotal = 0;
        $orderProductsData = collect();

        $order->vat_percentage = self::getVatPercentageForSpecificCountry($order->billing_country);

        foreach ($groupedProductData as $morphKey => $productsData) {
            $orderableModel = MorphMap::getModelByKey($morphKey);
            if ($orderableModel === null) {
                throw new Exception("Unknown orderable model morph key: " . $morphKey);
            }
            $products = $orderableModel::whereIn('id', $productsData->pluck("id"))->get();
            $groupedProducts->put($morphKey, $products);

            foreach ($productsData as $productData) {
                $product = $products->firstWhere('id', $productData['id']);
                if ($product === null) {
                    throw new Exception("Product not found: " . $productData['id']);
                }

                $productTotalNoVat = $productData['quantity'] * $product->price;
                $productVat = round($productTotalNoVat * $order->vat_percentage / 100, 2);
                $productTotalWithVat = $productTotalNoVat + $productVat;

                $subtotal += $productTotalNoVat;

                $orderProductsData->push([
                    'orderable_id' => $product->id,
                    'orderable_type' => $morphKey,
                    'quantity' => $productData['quantity'],
                    'name' => $product->order_name,
                    'price_per_unit' => $productData['price'],
                    'discount_amount_per_unit' => $productData['discount'],
                    'total_no_vat' => $productTotalNoVat,
                    'vat_percentage' => $order->vat_percentage,
                    'vat_amount' => $productVat,
                    'total_with_vat' => $productTotalWithVat,
                ]);
            }
        }

        $shippingType = ShippingType::where("id", $orderData['shipping_type_id'])->first();
        if (!$shippingType) {
            throw new Exception("Nebol nájdený typ dopravy s ID " . $orderData['shipping_type_id']);
        }
        $order->shipping_type_price = $shippingType->price;
        $order->shipping_type_label = $shippingType->name;

        $paymentType = PaymentType::where("id", $orderData['payment_type_id'])->first();
        if (!$paymentType) {
            throw new Exception("Nebol nájdený typ platby s ID " . $orderData['payment_type_id']);
        }
        $order->payment_type_price = $paymentType->price;
        $order->payment_type_label = $paymentType->name;

        $order->subtotal = $subtotal;
        $order->total_no_vat = $order->subtotal + $order->shipping_type_price + $order->payment_type_price ;
        $order->vat_amount = round($order->total_no_vat * $order->vat_percentage / 100, 2);
        $order->total_with_vat = $order->total_no_vat + $order->vat_amount;
        $order->discount_amount = 0;

        $order->save();

        foreach ($orderProductsData as $orderProductData) {
            $order->orderItems()->create($orderProductData);
        }

        $order->load("orderItems");

        return $order;
    }

    public static function getVatPercentageForSpecificCountry(OrderCountry $billingCountry): int
    {
        $baseBillingCountry = config("order.base_billing_country");
        $baseCountryVAT = config("order.vat_percentages." . $baseBillingCountry->value);
        if ($billingCountry === $baseBillingCountry) {
            return $baseCountryVAT;
        }

        // Here add branch when needed to add foreign country VAT

        return $baseCountryVAT;
    }

    /**
     * @throws Exception
     */
    public static function resendOrderCreatedNotification(Order $order): void
    {
        try {
            $order->notify(new OrderCreated($order));

            Notification::make()
                ->title('Úspešne odoslané.')
                ->body('Potvrdenie o objednávke bolo úspešne znovu odoslané na zákazníkov email.')
                ->success()
                ->send();

        } catch (Exception $e) {

            if(app()->hasDebugModeEnabled()) {
                throw $e;
            } else {
                Log::critical($e->getMessage());
                Notification::make()
                    ->title('Niečo sa nepodarilo.')
                    ->body('Kontaktuj prosím Miša :P #resendOrderNotification.')
                    ->danger()
                    ->send();
            }

        }
    }
}
