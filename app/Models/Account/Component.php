<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{


    protected $fillable = ['name', 'tag_id'];


    public function qualitySystems()
    {
        return $this->belongsToMany('App\Models\QualitySystem\QualitySystem')->withPivot(['url', 'type']);
    }

    public function hierarchicalTag()
    {
        return $this->belongsTo('App\Models\TagTree\HierarchicalTag', 'tag_id');
    }


}
