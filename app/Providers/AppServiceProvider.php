<?php

namespace App\Providers;

use App\Misc\MorphMap;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Relation::morphMap(MorphMap::make());

        Model::preventLazyLoading(! app()->isProduction());

        JsonResource::withoutWrapping();
    }
}
