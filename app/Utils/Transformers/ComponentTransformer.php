<?php

namespace App\Utils\Transformers;

use Carbon\Carbon;

class ComponentTransformer extends Transformer
{

    public function transform($component)
    {

        return [
            'id'  => $component->id,
            'name'  => $component->name,
            'type_id' => $component->hierarchicalTag->typeTag->id,
            'parent_id' => $component->hierarchicalTag->parent_id
        ];
    }
}