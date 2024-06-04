<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiangRepairDate extends Model
{
    use HasFactory;
    protected $table = 'tbl_savr_repair_dates';

    public function repairDates()
    {
        return $this->belongsTo(Tiang::class, 'savr_id');
    }
}
