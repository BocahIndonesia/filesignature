<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyModel extends Model
{
    public $timestamps= false;
    
    static function modelName(){
        return basename(str_replace('\\', '/', static::class));
    }
}
