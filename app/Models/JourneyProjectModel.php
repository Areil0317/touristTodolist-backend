<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JourneyProjectModel extends Model
{
    protected $table = 'journeyproject';
    protected $primaryKey = 'jpid';
    public $timestamps = false;
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "jpchecked" => "boolean",
    ];
    /**
     * Mutate the attribute value to boolean.
     * "1" is true and "0" is false.
     *
     * @param  mixed  $value
     * @return bool
     */
    public function getJpcheckecAttribute($value)
    {
        return $value === "1";
    }

    public function project()
    {
        return $this->belongsTo(ProjectModel::class,'pid');
    }
}
