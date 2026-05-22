<?php

namespace App\Misc;

use App\Models\Event;
use App\Models\Form;
use App\Models\FormField;
use App\Models\FormSubmissionField;
use App\Models\Order;
use App\Models\Post;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $media->loadMissing("model");
        $mod = $media->model;
        $modelClass = class_basename($mod);
        $modID = $mod->id;

        return match ($media->model_type) {
            MorphMap::getKeyByModel(Post::class) => "{$mod->storage_base_path}/{$media->collection_name}/",
            MorphMap::getKeyByModel(Event::class) => "{$mod->storage_base_path}/{$media->collection_name}/",
            MorphMap::getKeyByModel(Form::class) => "formulare/{$mod->getTranslations("slug")['sk']}/{$media->collection_name}/",
            MorphMap::getKeyByModel(Order::class) => "objednavky/{$mod->fullOrderNumber}/{$media->collection_name}/",
            MorphMap::getKeyByModel(FormSubmissionField::class) => "objednavky/{$mod->formSubmission->order->fullOrderNumber}/formulare/{$mod->formSubmission->form->getTranslations("slug")['sk']}/",

            default => "nezaradene/" . Str::snake($modelClass) . "/{$modID}/{$media->collection_name}/",
        };
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . '/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . '/responsive/';
    }
}
