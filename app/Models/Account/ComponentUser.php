<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Model;

class ComponentUser extends Model
{
    protected $table = 'component_user';
    protected $fillable = ['user_id', 'component_id'];
}
