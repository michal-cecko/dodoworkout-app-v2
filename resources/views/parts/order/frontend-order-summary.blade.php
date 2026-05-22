<div class="order-summary space-y-4">
    <!-- Order Items Section -->
    <div class="bg-white border border-[#EDEDED] rounded-lg p-4">
        <h3 class="font-semibold text-lg mb-4">{{__("ord_section_summary_order_items_heading")}}</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($cartItems as $item)
                <div class="flex flex-col space-y-1">
                    <span class="text-gray-600 font-bold">{{ $item['name'] }}</span>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{__("ord_section_summary_order_items_quantity")}}</span>
                        <span class="text-gray-900 font-medium">{{ $item['quantity'] }}x</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{__("ord_section_summary_order_items_price")}}</span>
                        <span class="text-gray-900 font-medium">
                            @if(isset($item['last_price']))
                                <span class="line-through text-gray-500 mr-2">
                                    {{ number_format($item['last_price'], 2) }}€
                                </span>
                            @endif
                            <span>
                                {{ number_format($item['price'], 2) }}€
                            </span>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @foreach($data as $section => $fields)
        <div class="bg-white border border-[#EDEDED] rounded-lg p-4">
            <h3 class="font-semibold text-lg mb-4">{{ $section }}</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($fields as $label => $value)
                    <div>
                        <div class="text-gray-600">{{ $label }}</div>
                        <div class="text-gray-900 font-medium">
                            @include('parts.order.frontend-order-summary-value', ['value' => $value])
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
