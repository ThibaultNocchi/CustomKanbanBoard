<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $hidden = ['id', 'card_id', 'created_at', 'updated_at'];

    public static function register(Card $c, string $name) {

        $task = new self();
        $task->name = $name;
        $max = $c->tasks()->max('order');
        if($max === null) $task->order = 0;
        else $task->order = $max + 1;
        $c->tasks()->save($task);
        return $task;

    }

    public function card(){
        return $this->belongsTo('App\Card');
    }

    public function scopeOrdered($query) {
        return $query->orderBy('order');
    }
}
