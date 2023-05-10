<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renewal extends Model
{
    use HasFactory;

    protected $table = 'renewal';

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
