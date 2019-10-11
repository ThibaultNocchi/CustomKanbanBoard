<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class Board extends Model
{
    protected $hidden = ['created_at', 'updated_at', 'id'];
    protected $appends = ['type'];

    public static function generateRandomString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function login(string $code): ?Board {
        $board = self::withCode($code)->first();
        return $board;
    }

    public static function register(string $name): Board {
        $board = new Board();
        $board->name = $name;
        $newCode = null;
        do{
            $newCode = self::generateRandomString();
        }while(self::withCode($newCode)->first() != null);
        $board->code = $newCode;
        $board->save();
        return $board;
    }

    public function users() {
        return $this->hasMany('App\User');
    }

    public function getTypeAttribute(){
        return (new ReflectionClass($this))->getShortName();
    }

    public function scopeWithCode($query, string $code) {
        return $query->where('code', $code);
    }

}
