<?php

namespace App;

use App\Exceptions\InvalidNameException;
use App\Exceptions\NoLineException;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{

    protected $hidden = ['board_id', 'created_at', 'updated_at'];
    protected $appends = ['tasks'];

    public static function register(Board $b, string $name)
    {

        $card = new self();
        if ($b->cards()->onName($name)->exists()) throw new InvalidNameException('Card name taken.');
        $card->name = $name;
        $max = $b->cards()->max('order');
        if ($max === null) $card->order = 0;
        else $card->order = $max + 1;
        $b->cards()->save($card);
        return $card;
    }

    public function remove_card()
    {
        $order = $this->order;
        $board = $this->board;
        $this->delete();
        $board->cards()->afterOrder($order)->decrement('order');
    }

    public function switch_to(int $order)
    {
        $count = $this->board->cards()->count();
        if ($order < 0) $order = 0;
        if ($order >= $count) $order = $count - 1;
        if ($order !== $this->order) {
            $originalOrder = $this->order;
            $this->order = $count;
            $this->save();
            if ($order < $originalOrder) {
                $this->board->cards()->where('order', '>=', $order)->where('order', '<', $originalOrder)->orderBy('order', 'desc')->increment('order');
            } else {
                $this->board->cards()->where('order', '>', $originalOrder)->where('order', '<=', $order)->orderBy('order')->decrement('order');
            }
            $this->order = $order;
            $this->save();
        }
    }

    public function getTasksAttribute()
    {
        return $this->tasks()->ordered()->get();
    }

    public function board()
    {
        return $this->belongsTo('App\Board');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function scopeOnName($query, string $name)
    {
        return $query->where('name', $name);
    }

    public function scopeOnOrder($query, int $order)
    {
        return $query->where('order', $order);
    }

    public function scopeAfterOrder($query, int $order)
    {
        return $query->where('order', '>', $order);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
