<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JourneyModel extends Model
{
    protected $table = 'journey';
    protected $primaryKey = 'jid';
    public $timestamps = false;
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "jchecked" => "boolean",
    ];
    /**
     * Mutate the attribute value to boolean.
     * "1" is true and "0" is false.
     *
     * @param  mixed  $value
     * @return bool
     */
    public function getJcheckedAttribute($value)
    {
        return $value === "1";
    }

    public function journeyProjects()
    {
        return $this->hasMany(JourneyProjectModel::class,'jid');
    }

    public function attraction()
    {
        return $this->belongsTo(AttractionModel::class,'aid');
    }
}
