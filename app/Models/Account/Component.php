<?php

namespace App\Models\Account;

use App\Utils\Helpers\NodeTraitExt;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use NodeTraitExt;

    protected $fillable = ['name', 'tag_id'];


    public function qualitySystems()
    {
        return $this->belongsToMany('App\Models\QualitySystem\QualitySystem')->withPivot(['url','type']);
    }


    public function root()
    {
        if(is_null($this->parent_id)){
            return $this;
        }

        return Component::from('components')
            ->where('_lft', '<', $this->_lft)
            ->where('_rgt', '>', $this->_rgt)
            ->whereNull('parent_id')->get()->first;
    }

}
