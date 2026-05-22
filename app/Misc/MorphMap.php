<?php

namespace App\Misc;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Form;
use App\Models\FormField;
use App\Models\FormSubmission;
use App\Models\FormSubmissionField;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentType;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\ShippingType;
use App\Models\User;

class MorphMap {
    public static function make(): array {
        return [
            "POST" => Post::class,
            "POST_TAG" => PostTag::class,
            "EVENT_CATEGORY" => EventCategory::class,
            "EVENT" => Event::class,
            "FORM" => Form::class,
            "FORM_SUBMISSION" => FormSubmission::class,
            "FORM_FIELD" => FormField::class,
            "FORM_SUBMISSION_FIELD" => FormSubmissionField::class,
            "ORDER" => Order::class,
            "ORDER_ITEM" => OrderItem::class,
            "PAYMENT_TYPE" => PaymentType::class,
            "SHIPPING_TYPE" => ShippingType::class,
            "USER" => User::class,
        ];
    }

    public static function getModelByKey(string $key): ?string {
        return self::make()[$key] ?? null;
    }

    public static function getKeyByModel(string $model): ?string {
        return array_search($model, self::make()) ?? null;
    }
}
