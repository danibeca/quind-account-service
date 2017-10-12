<?php

namespace App\Http\Controllers\TagTree;

use App\Models\TagTree\HierarchicalTag;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class RootTagController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->respond(HierarchicalTag::whereNull('parent_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tag = new HierarchicalTag ($request->except('parent_id'));
        if($request->has('parent_id')){
            $tag->appendToNode(HierarchicalTag::find($request->parent_id));
            $tag->save();
        }else{
            $tag->saveAsRoot();
        }

        HierarchicalTag::fixTree();

        return $this->respond($tag);
    }
}
