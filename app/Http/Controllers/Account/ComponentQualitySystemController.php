<?php

namespace App\Http\Controllers\Account;


use App\Http\Controllers\ApiController;
use App\Models\Account\Component;
use App\Models\Account\ComponentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ComponentQualitySystemController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($componentId)
    {
        if(Input::has('resources')){
            $qa = Component::find($componentId)->qualitySystems()->get()->first();
            return $this->respondData($qa->resources());
        }
        return $this->respondData(Component::find($componentId)->qualitySystems()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $componentId)
    {
        $component = Component::find($componentId);
        $verified = ($request->has('verified')) ? $request->verified : false;

        $component->qualitySystems()
            ->attach($request->id,
                ['url' => $request->url, 'type' => $request->type, 'verified' => $verified]);

        return $this->respondResourceCreated();
    }
}
