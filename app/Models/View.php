<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $hidden = [
        'id',
    ];

    protected $fillable = [
        'id_page',
        'view_date'
    ];
}
