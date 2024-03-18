<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JbudgetModel extends Model
{
    protected $table = 'jbudget';
    protected $primaryKey = 'jbid';
    public $timestamps = false;

}
