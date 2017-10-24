<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\ApiController;
use App\Models\Account\Component;
use App\Models\Account\ComponentTree;
use App\Models\Account\ComponentUser;
use Illuminate\Http\Request;

class UserRootComponentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userId)
    {
        $componentUser = ComponentUser::where('user_id', $userId)
            ->get()
            ->first();

        if (isset($componentUser))
        {
            $component = Component::find($componentUser->component_id);

            $tree = ComponentTree::where('component_id', $component->id)->get()->first();

            return $this->respondData([Component::find($tree->getRoot()->component_id)]);
        }

        return $this->respondNotFound();


    }

}
