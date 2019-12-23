<?php

namespace App;

use App\Exceptions\ValidationFailedException;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $hidden = ['card_id', 'created_at', 'updated_at', 'laravel_through_key'];
    protected $appends = ['users'];

    private $colors = ['red', 'pink', 'purple', 'blue', 'green', 'yellow', 'orange', 'brown', 'grey'];

    public static function register(Card $c, string $name)
    {

        $task = new self();
        $task->name = $name;
        $max = $c->tasks()->max('order');
        if ($max === null) $task->order = 0;
        else $task->order = $max + 1;
        $task->color = 'grey';
        $c->tasks()->save($task);
        return $task;
    }

    public function remove_task()
    {
        $order = $this->order;
        $card = $this->card;
        $this->delete();
        $card->tasks()->afterOrder($order)->decrement('order');
    }

    public function switch_to(int $order)
    {
        $count = $this->card->tasks()->count();
        if ($order < 0) $order = 0;
        if ($order >= $count) $order = $count - 1;
        if ($order !== $this->order) {
            $originalOrder = $this->order;
            $this->order = $count;
            $this->save();
            if ($order < $originalOrder) {
                $this->card->tasks()->where('order', '>=', $order)->where('order', '<', $originalOrder)->orderBy('order', 'desc')->increment('order');
            } else {
                $this->card->tasks()->where('order', '>', $originalOrder)->where('order', '<=', $order)->orderBy('order')->decrement('order');
            }
            $this->order = $order;
            $this->save();
        }
    }

    public function switch_into(Card $other_card, int $new_order)
    {
        if (!$other_card->is($this->card)) {
            $other_card_count = $other_card->tasks()->count();
            $original_order = $this->order;
            $original_card = $this->card;
            $this->order = $other_card_count;
            $other_card->tasks()->save($this);
            $original_card->tasks()->where('order', '>', $original_order)->decrement('order');
            $this->refresh();
            $this->switch_to($new_order);
        }
    }

    public function getUsersAttribute()
    {
        return $this->users()->orderBy('user_id')->get()->pluck('id');
    }

    public function setColorAttribute(string $val)
    {
        if (in_array(strtolower($val), $this->colors)) {
            $this->attributes['color'] = strtolower($val);
        } else {
            throw new ValidationFailedException('Color not in array.');
        }
    }

    public function card()
    {
        return $this->belongsTo('App\Card');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeAfterOrder($query, int $order)
    {
        return $query->where('order', '>', $order);
    }
}
