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

    public function jbudgets()
    {
        return $this->hasMany(JbudgetModel::class,'jid');
    }

    public function jimages()
    {
        return $this->hasMany(JimageModel::class,'jid');
    }

    public function attraction()
    {
        return $this->belongsTo(AttractionModel::class,'aid');
    }
}
