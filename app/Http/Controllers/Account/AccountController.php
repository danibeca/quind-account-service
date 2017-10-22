<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\ApiController;
use App\Models\Account\Component;
use App\Models\Account\ComponentTree;
use App\Utils\Transformers\ComponentTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class AccountController extends ApiController
{
    public function store()
    {
        $newComponent = new Component ();
        $newComponent->name = Input::get('name');
        $newComponent->tag_id = 1;
        $newComponent->save();
        $newComponentTree = new ComponentTree();
        $newComponentTree->component_id = $newComponent->id;
        $newComponentTree->saveAsRoot();

        ComponentTree::fixTree();

        return $this->respondResourceCreated((new ComponentTransformer())->transform($newComponent));
    }
}
