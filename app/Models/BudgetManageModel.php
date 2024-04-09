<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetManageModel extends Model
{
    use HasFactory;

    protected $table = 'budgetmanage';

    protected $primaryKey = 'bmid';
    public $timestamps = false;

    public function partners()
    {
        return $this->hasMany(PartnerModel::class, 'bmid');
    }


}
