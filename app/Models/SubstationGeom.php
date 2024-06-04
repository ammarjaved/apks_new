<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubstationGeom extends Model
{
    use HasFactory;
    public $table = 'tbl_substation';
    protected $fillable = ['id', 'geom'];

    public function Substations() {
        return $this->belongsTo(Substation::class, 'geom_id');
    }
}
