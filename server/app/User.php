<?php

namespace App;

use App\Exceptions\InvalidNameException;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class User extends Model
{

    protected $hidden = ['created_at', 'updated_at', 'id', 'board_id'];

    public static function register(Board $b, string $name) {
        if($b->users()->onName($name)->first() !== null) {
            throw new InvalidNameException();
        }
        $user = new User();
        $user->name = $name;
        $b->users()->save($user);
        return $user;
    }
    
    public function board(){
        return $this->belongsTo('App\Board');
    }

    public function scopeOnName($query, string $name) {
        return $query->where('name', $name);
    }

}
