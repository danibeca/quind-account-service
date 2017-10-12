<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\ApiController;
use App\Models\Account\Component;
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
        $component = Component::find(ComponentUser::where('user_id', $userId)
            ->get()
            ->first()->component_id);

        return $this->respond($component->root());
    }

}
