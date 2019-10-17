<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{

    protected $hidden = ['id', 'board_id', 'created_at', 'updated_at'];
    
    public function board() {
        return $this->belongsTo('App\Board');
    }
}
