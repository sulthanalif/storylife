<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'galleries'; // Specify the table name
    
    protected $fillable = [
        'tittle', 'description', 'file'
    ];
}
