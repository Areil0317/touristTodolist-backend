<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JpimageModel extends Model
{
    protected $table = 'jpimage';
    protected $primaryKey = 'jpiid';
    public $timestamps = false;

    protected $fillable = ['jpid', 'jpimg'];

    public function journeyProject()
    {
        return $this->belongsTo(JourneyProjectModel::class, 'jpid');
    }
}
