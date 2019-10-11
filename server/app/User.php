<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class User extends Model
{

    protected $hidden = ['created_at', 'updated_at', 'id', 'board_id'];
    protected $appends = ['type'];
    
    public function board(){
        return $this->belongsTo('App\Board');
    }

    public function getTypeAttribute(){
        return (new ReflectionClass($this))->getShortName();
    }

}
