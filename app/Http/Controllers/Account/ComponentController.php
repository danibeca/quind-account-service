<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\ApiController;
use App\Models\Account\Component;
use App\Models\Account\ComponentTree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ComponentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Input::has('parent_id'))
        {
            $ids = ComponentTree::find(Input::get('parent_id'))->getDescendants()->pluck('component_id');
            return $this->respondData(Component::whereIn('id', $ids)->get());
        }

        return $this->respondData(Component::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newComponent = new Component ($request->except('parent_id'));
        $newComponent->save();
        if ($request->has('parent_id') && $request->tag_id !== 1)
        {
            $newComponentTree = new ComponentTree();
            $newComponentTree->component_id = $newComponent->id;
            $newComponentTree->appendToNode(ComponentTree::where('component_id',$request->parent_id)->first())->save();

        } else
        {
            $newComponentTree = new ComponentTree();
            $newComponentTree->component_id = $newComponent->id;
            $newComponentTree->saveAsRoot();
        }

        ComponentTree::fixTree();

        return $this->respondResourceCreated($newComponent);

    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->respondData(Component::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $component = Component::find($id);
        if (! $component->isRoot())
        {
            $component->update($request->only('name'));
        } else
        {
            $component->update($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $component = Component::find($id);
        if (! $component->isRoot())
        {
            Component::find($id)->delete();
        }

        return $this->respondResourceDeleted();
    }
}
