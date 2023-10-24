<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use UUIDGenerator, SoftDeletes;

    protected $table = 'services'; // Specify the table name
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = [
        'name', 'description', 'image', 'status_id'
    ];


    public function status()
    {
        return $this->hasOne(Status::class);
    }
}
