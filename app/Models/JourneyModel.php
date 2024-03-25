<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JourneyModel extends Model
{
    protected $table = 'journey';
    protected $primaryKey = 'jid';
    public $timestamps = false;

    public function journeyProjects()
    {
        return $this->hasMany(JourneyProjectModel::class,'jid');
    }

    public function attraction()
    {
        return $this->belongsTo(AttractionModel::class,'aid');
    }
}
