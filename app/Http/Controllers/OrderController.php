<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\Order\StoreOrderDTO;
use App\DataTransferObjects\Order\OrderBillingDataDTO;
use App\DataTransferObjects\Order\OrderShippingDataDTO;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * @throws Exception
     */
    public function storeOrder(StoreOrderRequest $request, OrderService $orderService): Response
    {
        $data = $request->validated();

        $billingData = new OrderBillingDataDTO(
            email: $data['email'],
            billing_first_name: $data['billing_first_name'],
            billing_last_name: $data['billing_last_name'],
            billing_address: $data['billing_address'],
            billing_city: $data['billing_city'],
            billing_zip: $data['billing_zip'],
            billing_country: $data['billing_country'],
            billing_phone: $data['billing_phone'],

            is_company: $data['is_company'],
            company_name: $data['company_name'] ?? null,
            business_id: $data['business_id'] ?? null,
            tax_id: $data['tax_id'] ?? null,
            vat_id: $data['vat_id'] ?? null,
        );

        $shippingData = new OrderShippingDataDTO(
            is_shipping_address: $data['is_shipping_address'],
            shipping_first_name: $data['shipping_first_name'] ?? null,
            shipping_last_name: $data['shipping_last_name'] ?? null,
            shipping_address: $data['shipping_address'] ?? null,
            shipping_city: $data['shipping_city'] ?? null,
            shipping_zip_code: $data['shipping_zip_code'] ?? null,
            shipping_country: $data['shipping_country'] ?? null,
            shipping_phone: $data['shipping_phone'] ?? null,
        );

        $orderData = new StoreOrderDTO(
            billing: $billingData,
            shipping: $shippingData,
            shipping_type_id: $data['shipping_type_id'],
            payment_type_id: $data['payment_type_id'],
            note: $data['note'] ?? null,
            marketing: $data['marketing'],
            user_id: $request->user()?->id,
            locale: $data['locale'],
        );

        $orderService->storeOrder(
            orderDTO: $orderData,
            products: collect($data['products']),
        );

        return response()->noContent();
    }
}
