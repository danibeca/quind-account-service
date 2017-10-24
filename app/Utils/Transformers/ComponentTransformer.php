<?php

namespace App\Utils\Transformers;



class ComponentTransformer extends Transformer
{

    public function transform($component)
    {

        return [
            'id'  => $component->id,
            'name'  => $component->name,
            'type_id' => $component->hierarchicalTag->typeTag->id,
            'parent_id' => $component->componentTree->parent_id
        ];
    }
}