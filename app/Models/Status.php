<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use UUIDGenerator, SoftDeletes;

    protected $table = 'statuses'; // Specify the table name
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'name', 'description'
    ];

    public function service() 
    {
        return $this->belongsTo(Service::class);
    }
    public function sosmed() 
    {
        return $this->belongsTo(Sosmed::class);
    }
    public function gallery() 
    {
        return $this->belongsTo(Gallery::class);
    }
}
