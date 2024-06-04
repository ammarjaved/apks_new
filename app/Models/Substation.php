<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Substation extends Model
{
    use HasFactory;
    public $table = 'tbl_substation';
    protected $fillable = ['id', 'zone', 'ba', 'team', 'visit_date','patrol_time',
    'fl', 'voltage', 'name', 'type', 'coordinate', 'gate_status',
    'grass_status', 'tree_branches_status', 'building_status',
    'advertise_poster_status', 'updated_at', 'created_at', 'geom',
    'image_gate', 'image_grass', 'image_tree_branches', 'images_gate_after_lock', 'image_building', 'other_image' , 'total_defects','reject_remakrs','geom_id', 'cycle'];

    public function SubstationGeom(){

        return $this->hasMany(SubstationGeom::class, 'geom_id');

    }
}
