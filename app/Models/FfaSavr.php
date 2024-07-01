<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FfaSavr extends Model
{
    use HasFactory;
    public $table = 'tbl_savr_ffa';
    protected $fillable = ['id', 'pole_id', 'wayar_tertanggal', 'ipc_terbakar', 'other', 'other_name', 'geom', 'pole_no', 'house_image',
    'ba', 'cycle', 'joint_box', 'house_renovation'];

    // public function SubstationGeom(){

    //     return $this->hasMany(SubstationGeom::class, 'geom_id');

    // }
}
