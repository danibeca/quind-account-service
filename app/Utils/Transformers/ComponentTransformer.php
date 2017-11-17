<?php

namespace App\Utils\Transformers;


use App\Models\Account\Component;
use App\Models\Account\ComponentTree;

class ComponentTransformer extends Transformer
{
    public function transform($component)
    {
        $component = Component::find($component['id']);

        return [
            'id'        => $component->id,
            'name'      => $component->name,
            'tag_name'  => $component->hierarchicalTag->name,
            'tag_id'    => $component->hierarchicalTag->id,
            'type_id'   => $component->hierarchicalTag->typeTag->id,
            'parent_id' => ComponentTree::find($component->componentTree->parent_id)->component_id
        ];
    }
}
