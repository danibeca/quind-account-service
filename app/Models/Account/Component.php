<?php

namespace App\Models\Account;

use App\Models\TagTree\TagType;
use function FastRoute\TestFixtures\empty_options_cached;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{


    protected $fillable = ['name', 'tag_id'];


    public function hierarchicalTag()
    {
        return $this->belongsTo('App\Models\TagTree\HierarchicalTag', 'tag_id');
    }

    public function componentTree()
    {
        //TODO Change when multiple parents
        return $this->hasOne('App\Models\Account\ComponentTree', 'component_id');
    }

    public function hasLeaves()
    {
        $result = false;

        if ($this->getLeaves()->count() > 0)
        {
            $result = true;
        }

        return $result;

    }

    public function getLeaves()
    {
        $result = collect();
        $tree = ComponentTree::where('component_id', $this->id)->get()->first()->getDescendants();
        if ($tree->count() > 0)
        {
            $ids = $tree->pluck('component_id');
            $result = Component::whereIn('id', $ids)->whereHas('hierarchicalTag', function ($query) {
                $query->where('type_id', 3);
            })->get();

        }

        return $result;

    }


}
