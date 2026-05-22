<?php

namespace App\Enums;

use App\Traits\Translations\TranslatableEnum;

enum FormFieldFormat : string
{
    use TranslatableEnum;

    case TEXT = "TEXT";
    case BOOL = "BOOL";
    case NUMBER = "NUMBER";
    case PHONE = "PHONE";
    case DATE = "DATE";
    case TIME = "TIME";
    case DATETIME = "DATETIME";
    case SELECT = "SELECT";
    case CHECKBOX = "CHECKBOX";
    case FILE = "FILE";
}
