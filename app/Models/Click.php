<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $hidden = [
        'id',
    ];

    protected $fillable = [
        'id_link',
        'click_date'
    ];
}
