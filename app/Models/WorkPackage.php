<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkPackage extends Model
{
    use HasFactory;
    public $table = 'tbl_workpackage';
    protected $fillable = ['package_name', 'geom', 'zone', 'ba', 'created_at', 'updated_at', 'created_by','wp_status' ];

    public function Diging() {
        return $this->hasMany(ThirdPartyDiging::class, 'workpackage_id');
    }


    public function Roads() {
        return $this->hasMany(Road::class, 'id_workpackage');
    }

}
