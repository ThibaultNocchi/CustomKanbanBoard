<?php

namespace App;

use App\Exceptions\InvalidNameException;
use App\Exceptions\NoLineException;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{

    protected $hidden = ['id', 'board_id', 'created_at', 'updated_at'];
    protected $appends = ['tasks'];

    public static function register(Board $b, string $name) {

        $card = new self();
        if($b->cards()->onName($name)->exists()) throw new InvalidNameException('Card name taken.');
        $card->name = $name;
        $max = $b->cards()->max('order');
        if($max === null) $card->order = 0;
        else $card->order = $max + 1;
        $b->cards()->save($card);
        return $card;

    }

    public function remove_card() {
        $order = $this->order;
        $board = $this->board;
        $this->delete();
        $board->cards()->afterOrder($order)->decrement('order');
    }

    public function switch_with(self $other) {
        if(!$this->board->is($other->board)) {
            throw new NoLineException('Problem matching boards.');
        }
        $order1 = $this->order;
        $order2 = $other->order;
        $orderTmp = $this->board->cards()->max('order') + 1;
        $this->order = $orderTmp;
        $other->order = $order1;
        $this->save();
        $other->save();
        $this->order = $order2;
        $this->save();
    }

    public function getTasksAttribute(){
        return $this->tasks()->ordered()->get();
    }
    
    public function board() {
        return $this->belongsTo('App\Board');
    }

    public function tasks(){
        return $this->hasMany('App\Task');
    }

    public function scopeOnName($query, string $name){
        return $query->where('name', $name);
    }

    public function scopeOnOrder($query, int $order) {
        return $query->where('order', $order);
    }

    public function scopeAfterOrder($query, int $order) {
        return $query->where('order', '>', $order);
    }

    public function scopeOrdered($query) {
        return $query->orderBy('order');
    }

}
