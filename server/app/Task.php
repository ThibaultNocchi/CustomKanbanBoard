<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $hidden = ['card_id', 'created_at', 'updated_at', 'laravel_through_key'];

    public static function register(Card $c, string $name) {

        $task = new self();
        $task->name = $name;
        $max = $c->tasks()->max('order');
        if($max === null) $task->order = 0;
        else $task->order = $max + 1;
        $c->tasks()->save($task);
        return $task;

    }

    public function remove_task() {
        $order = $this->order;
        $card = $this->card;
        $this->delete();
        $card->tasks()->afterOrder($order)->decrement('order');
    }

    public function card(){
        return $this->belongsTo('App\Card');
    }

    public function scopeOrdered($query) {
        return $query->orderBy('order');
    }

    public function scopeAfterOrder($query, int $order) {
        return $query->where('order', '>', $order);
    }
}
