<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkBox extends Model
{
    use HasFactory;
    public $table = "tbl_link_box";

    protected $fillable = [
        'zone', 'ba', 'team', 'visit_date', 'patrol_time', 'feeder_involved', 'area', 'start_date', 'end_date', 'type', 'coordinate', 'gate_status', 'vandalism_status', 'leaning_staus', 'rust_status', 'advertise_poster_status', 'bushes_status', 'geom', 'image_gate', 'image_vandalism', 'image_leaning', 'image_rust', 'images_advertise_poster', 'images_bushes', 'other_image'
    ];
}
