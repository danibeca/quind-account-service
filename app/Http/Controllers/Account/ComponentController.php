<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\ApiController;
use App\Models\Account\Component;
use App\Models\Account\ComponentTree;
use App\Utils\Transformers\ComponentTransformer;
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
            $node = ComponentTree::find(Input::get('parent_id'));
            $parent = Component::find($node->component_id);
               $ids = $node->getDescendants()->pluck('component_id');

            $result = Component::whereIn('id', $ids)->get();
            if(Input::has('self_included') && Input::get('self_included')){
                $result = $result->push($parent);
            }

            if(Input::has('no_leaves')){
                $result = $result->diff($parent->getLeaves());
            }

            if(Input::has('only_leaves')){
                $result = Component::find(Input::get('parent_id'))->getLeaves();
            }

            return $this->respondData(array_values($result->sortBy('tag_id')->toArray()));
        }

        return $this->respondNotFound();
    }


    public function store(Request $request)
    {
        $newComponent = new Component ($request->except('parent_id'));
        $newComponent->save();
        if ($request->has('parent_id') && $request->tag_id !== 1)
        {
            $newComponentTree = new ComponentTree();
            $newComponentTree->component_id = $newComponent->id;
            $newComponentTree->appendToNode(ComponentTree::where('component_id', $request->parent_id)->first())->save();

        } else
        {
            $newComponentTree = new ComponentTree();
            $newComponentTree->component_id = $newComponent->id;
            $newComponentTree->saveAsRoot();
        }

        ComponentTree::fixTree();

        return $this->respondResourceCreated((new ComponentTransformer())->transform($newComponent));

    }


    public function show($id)
    {
        if (Input::has('hasLeaves'))
        {
            $result = false;
            $component = Component::find($id);
            if (isset($component))
            {
                $result = $component->hasLeaves();
            }

            return $this->respond($result);
        }

        return $this->respondData(Component::find($id));
    }


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
