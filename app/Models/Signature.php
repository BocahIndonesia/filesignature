<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Signature extends MyModel
{
    use HasFactory;

    protected $table= 'Signature';
    protected $fillable= ['offset', 'hex', 'extension_id'];

    public function extension():BelongsTo{
        return $this->belongsTo(Extension::class);
    }
}
