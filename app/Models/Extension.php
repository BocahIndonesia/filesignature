<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsTo};

class Extension extends MyModel
{
    use HasFactory;
    protected $table= 'Extension';
    protected $fillable= ['name', 'mime_id'];

    public function mime():BelongsTo{
        return $this->belongsTo(Mime::class);
    }
    
    public function signatures():HasMany{
        return $this->hasMany(Signature::class);
    }

}
