<?php

namespace App;

use App\Exceptions\InvalidNameException;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{

    protected $hidden = ['id', 'board_id', 'created_at', 'updated_at'];

    public static function register(Board $b, string $name) {

        $card = new self();
        if($b->cards()->onName($name)->exists()) throw new InvalidNameException('Card name taken.');
        $card->name = $name;
        $card->order = ($b->cards()->max('order')) + 1;
        $b->cards()->save($card);
        return $card;

    }
    
    public function board() {
        return $this->belongsTo('App\Board');
    }

    public function scopeOnName($query, string $name){
        return $query->where('name', $name);
    }

    public function scopeOnOrder($query, int $order) {
        return $query->where('order', $order);
    }

    public function scopeOrdered($query) {
        return $query->orderBy('order');
    }

}
