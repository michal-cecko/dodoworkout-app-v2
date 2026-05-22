<?php

namespace App\Enums;

enum ShippingTypeEnum : string
{
    case EMAIL = "EMAIL";
    case COURIER = "COURIER";
    case PERSON = "PERSON";
}
