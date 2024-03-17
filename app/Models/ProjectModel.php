<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectModel extends Model
{
    protected $table = 'project';

    protected $primaryKey = 'pid';
    public $timestamps = false;

    protected $fillable = [
        'pname',
        'aid'
    ];
}
