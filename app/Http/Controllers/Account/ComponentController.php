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
    public function index()
    {
        if (Input::has('parent_id'))
        {
            $node = ComponentTree::where('component_id', Input::get('parent_id'))->get()->first();
            if($node){
                $parent = Component::find($node->component_id);
                $ids = $node->getDescendants()->pluck('component_id');
                /** @var Component $result */
                $result = Component::whereIn('id', $ids)->get();
                if (Input::has('self_included') && Input::get('self_included'))
                {
                    $result = $result->push($parent);
                }

                if (Input::has('no_leaves'))
                {
                    $result = $result->diff($parent->getLeaves());
                }

                if (Input::has('only_leaves'))
                {
                    $result = Component::find(Input::get('parent_id'))->getLeaves();
                }

                return $this->respondData((new ComponentTransformer())->transformCollection(array_values($result->sortBy('tag_id')->toArray())));
            }

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
        /** @var Component $component */
        $component = Component::find($id);
        /** @var ComponentTree $componentTree */
        $componentTree = ComponentTree::where('component_id', $component->id)->get()->first();
        if ($componentTree->isRoot())
        {
            $component->update($request->only('name'));
        } else
        {
            $component->update($request->all());
            if ($request->parent_id)
            {
                $componentTree->parent_id = $request->parent_id;
                $componentTree->save();
                ComponentTree::fixTree();
            }
        }

        return $this->respondResourceCreated((new ComponentTransformer())->transform($component));
    }

    public function destroy($id)
    {
        /** @var Component $component */
        $component = Component::find($id);
        /** @var ComponentTree $componentTree */
        $componentTree = ComponentTree::where('component_id', $component->id)->get()->first();
        if (! $componentTree->isRoot())
        {
            Component::whereIn('id',$componentTree->getDescendants()->pluck('component_id'))->delete();
            Component::find($id)->delete();
            ComponentTree::fixTree();
        }

        return $this->respondResourceDeleted();
    }
}
