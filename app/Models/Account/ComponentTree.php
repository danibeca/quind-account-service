<?php

namespace App\Models\Account;

use App\Utils\Helpers\NodeTraitExt;
use Illuminate\Database\Eloquent\Model;

class ComponentTree extends Model
{
    use NodeTraitExt;

    protected $fillable = ['name', 'tag_id'];

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
