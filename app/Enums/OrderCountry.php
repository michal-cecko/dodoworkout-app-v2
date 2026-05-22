<?php

namespace App\Enums;

use App\Traits\Translations\TranslatableEnum;

enum OrderCountry : string
{
    use TranslatableEnum;

    case SK = "SK"; // Slovakia
    case CZ = "CZ"; // Czech Republic
    case AL = "AL"; // Albania
    case AD = "AD"; // Andorra
    case AT = "AT"; // Austria
    case BY = "BY"; // Belarus
    case BE = "BE"; // Belgium
    case BA = "BA"; // Bosnia and Herzegovina
    case BG = "BG"; // Bulgaria
    case HR = "HR"; // Croatia
    case CY = "CY"; // Cyprus
    case DK = "DK"; // Denmark
    case EE = "EE"; // Estonia
    case FI = "FI"; // Finland
    case FR = "FR"; // France
    case DE = "DE"; // Germany
    case GR = "GR"; // Greece
    case HU = "HU"; // Hungary
    case IS = "IS"; // Iceland
    case IE = "IE"; // Ireland
    case IT = "IT"; // Italy
    case LV = "LV"; // Latvia
    case LI = "LI"; // Liechtenstein
    case LT = "LT"; // Lithuania
    case LU = "LU"; // Luxembourg
    case MT = "MT"; // Malta
    case MD = "MD"; // Moldova
    case MC = "MC"; // Monaco
    case ME = "ME"; // Montenegro
    case NL = "NL"; // Netherlands
    case MK = "MK"; // North Macedonia
    case NO = "NO"; // Norway
    case PL = "PL"; // Poland
    case PT = "PT"; // Portugal
    case RO = "RO"; // Romania
    case RU = "RU"; // Russia
    case SM = "SM"; // San Marino
    case RS = "RS"; // Serbia
    case SI = "SI"; // Slovenia
    case ES = "ES"; // Spain
    case SE = "SE"; // Sweden
    case CH = "CH"; // Switzerland
    case UA = "UA"; // Ukraine
    case GB = "GB"; // United Kingdom
    case VA = "VA"; // Vatican City
}
