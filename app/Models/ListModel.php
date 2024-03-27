<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListModel extends Model
{
    protected $table = 'touristlist';
    protected $primaryKey = 'tlid';

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    public function journeys()
    {
        return $this->hasMany(JourneyModel::class, 'tlid');
    }


    public static function listTimeStamp()
    {
        parent::listTimeStamp();

        self::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
            $model->updated_at = $model->freshTimestamp();
        });

        self::updating(function ($model) {
            $model->updated_at = $model->freshTimestamp();
        });
    }
}
