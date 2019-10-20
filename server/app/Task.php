<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $hidden = ['id', 'card_id', 'created_at', 'updated_at'];

    public function card(){
        return $this->belongsTo('App\Card');
    }

    public function scopeOrdered($query) {
        return $query->orderBy('order');
    }
}
