<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttractionModel extends Model
{
    protected $table = 'attractions';

    protected $primaryKey = 'aid';
    public $timestamps = false;

    protected $fillable = [
        'aname'
    ];

    public function journeys()
    {
        return $this->hasMany(JourneyModel::class,'aid');
    }
    
}
