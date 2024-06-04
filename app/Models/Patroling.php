<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patroling extends Model
{
    use HasFactory;

    public $table = 'patroling';
    protected $fillable = ['cycle', 'date', 'time', 'geom', 'km', 'wp_name','ba', 'geom_start' ,'reading_start', 'reading_end' ,'image_reading_start' ,'image_reading_end' ,'created_by' , 'status' , 'geom_end'  ];

    public function patrollingLines() {
        return $this->hasMany(PatrollingLines::class, 'patroling_id');
    }

    public function firstPatrollingLines() {
        return $this->hasOne(PatrollingLines::class, 'patroling_id');
    }
}
