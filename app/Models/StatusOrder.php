<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusOrder extends Model
{
    use UUIDGenerator, SoftDeletes;

    protected $table = 'status_orders'; // Specify the table name
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'name'
    ];

    public function order() 
    {
        return $this->belongsTo(Service::class);
    }
}
