<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class User extends Model
{

    protected $hidden = ['created_at', 'updated_at', 'id', 'board_id'];
    
    public function board(){
        return $this->belongsTo('App\Board');
    }

}
