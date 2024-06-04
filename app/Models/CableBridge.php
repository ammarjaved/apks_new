<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CableBridge extends Model
{
    use HasFactory;
    public $table = "tbl_cable_bridge";

    protected $fillable = [
        'zone', 'ba', 'team', 'visit_date', 'patrol_time', 'feeder_involved', 'area', 'start_date', 'end_date', 'voltage', 'coordinate', 'vandalism_status', 'pipe_staus', 'collapsed_status', 'rust_status', 'bushes_status', 'geom', 'image_vandalism', 'image_pipe', 'image_collapsed', 'image_rust', 'images_bushes', 'other_image'
    ];


}
