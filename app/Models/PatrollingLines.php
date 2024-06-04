<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatrollingLines extends Model
{
    use HasFactory;
    public $table = 'patroling_lines';
    protected $fillable = ['patroling_id', 'geom', 'km', 'reading_start', 'reading_end', 'geom_start','geom_end', 'ba'  ];


    public function Patrolling()
    {
        return $this->hasMany(Patroling::class, 'patroling_id');
    }
    
}
