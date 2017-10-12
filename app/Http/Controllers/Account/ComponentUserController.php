<?php

namespace App\Http\Controllers\Account;


use App\Http\Controllers\ApiController;
use App\Models\Account\ComponentUser;
use Illuminate\Http\Request;

class ComponentUserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($componentId)
    {
        return $this->respond(ComponentUser::where('component_id', $componentId)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $componentId)
    {
        $componentUser = new ComponentUser();
        $componentUser->component_id = $componentId;
        $componentUser->user_id = $request->user_id;
        $componentUser->save();
    }
}
