<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeederPillar extends Model
{
    use HasFactory;

    public $table = "tbl_feeder_pillar";

    protected $fillable = [
        'id', 'zone', 'ba', 'team', 'visit_date', 'patrol_time', 
        'feeder_involved', 'area', 'size', 'coordinate', 'gate_status',
        'vandalism_status', 'leaning_staus', 'rust_status', 'advertise_poster_status',
        'created_at', 'updated_at', 'geom', 'image_gate', 'image_vandalism', 'image_leaning', 
        'image_rust', 'images_advertise_poster','other_image','total_defects'
    ];
}
