<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JourneyProjectModel extends Model
{
    protected $table = 'journeyproject';
    protected $primaryKey = 'jpid';
    public $timestamps = false;
    public function project()
    {
        return $this->belongsTo(ProjectModel::class, 'pid');
    }

    public function jpbudgets()
    {
        return $this->hasMany(JpbudgetModel::class, 'jpid');
    }

    public function jpimages()
    {
        return $this->hasMany(JpimageModel::class, 'jpid');
    }

    public function journey()
    {
        return $this->belongsTo(JourneyModel::class, 'jid');
    }
}
