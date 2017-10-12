<?php

namespace App\Models\TagTree;

use App\Utils\Helpers\NodeTraitExt;
use Illuminate\Database\Eloquent\Model;

class HierarchicalTag extends Model
{
    use NodeTraitExt;

    protected $fillable = ['name', 'type_id'];

}
