<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mime extends MyModel
{
    use HasFactory;

    protected $table= 'Mime';
    protected $fillable= ['name'];
    
    public function extensions():HasMany{
        return $this->hasMany(Extension::class);
    }
}
