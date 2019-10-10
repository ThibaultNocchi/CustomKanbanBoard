<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $hidden = ['created_at', 'updated_at', 'id'];

    public function scopeWithCode($query, string $code) {
        return $query->where('code', $code);
    }

}
