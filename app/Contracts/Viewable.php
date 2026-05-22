<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Casts\Attribute;

interface Viewable
{
    public function getPermalinkAttribute();
}
