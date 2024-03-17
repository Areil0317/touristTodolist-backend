<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListModel extends Model
{
    protected $table = 'touristlist';
    protected $primaryKey = 'tlid';

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
