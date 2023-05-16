<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;


    protected $table = 'contact';

    public function practitioner()
    {
        return $this->belongsTo(Practitioner::class);
    }

    public function contactType()
    {
        return $this->belongsTo(ContactType::class, 'contactType_id');
    }


}
