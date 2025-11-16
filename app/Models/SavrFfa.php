<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavrFfa extends Model
{
    use HasFactory;
    protected $table = 'tbl_savr_ffa_sub11';

    protected $fillable = [
         'id',
        'pole_id',
        'wayar_tertanggal',
        'ipc_terbakar',
        'other',
        'other_name',
        'pole_no',
        'house_image',
        'ba',
        'joint_box',
        'house_renovation',
        'house_number',
        'geom',
        'image2',
        'image3',
        'visit_date'
    ];



    /**
     * Get the Tiang that owns the SavrFfa
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Tiang()
    {
        return $this->belongsTo(Tiang::class, 'pole_id', 'id');
    }



}
