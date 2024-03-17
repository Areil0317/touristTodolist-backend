<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JpbudgetModel extends Model
{
    protected $table = 'jpbudget';
    protected $primaryKey = 'jpbid';
    public $timestamps = false;
}
