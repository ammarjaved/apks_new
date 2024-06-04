<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Road extends Model
{
    use HasFactory;
    public $table = 'tbl_roads';
    protected $fillable = ['road_name', 'geom', 'id_workpackage', 'created_by', 'created_at', 'updated_at', 'ba', 'zone', 'date_patrol', 'time_petrol', 'name_project', 'actual_km', 'fidar', 'total_digging', 'total_notice', 'total_supervision'];

    public function workPackage()
    {
        return $this->belongsTo(WorkPackage::class, 'id_workpackage');
    }
}
