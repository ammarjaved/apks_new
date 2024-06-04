<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiang extends Model
{
    use HasFactory;

    protected $table = 'tbl_savr';

    protected $fillable = [ 'ba', 'name_contractor', 'start_date', 'end_date', 'fp_name', 'review_date', 'fp_road', 'section_from', 'section_to', 'tiang_no', 'geom', 'size_tiang', 'jenis_tiang', 'abc_span', 'pvc_span', 'bare_span', 'tiang_defect', 'talian_defect', 'umbang_defect', 'ipc_defect', 'blackbox_defect', 'jumper', 'kilat_defect', 'servis_defect', 'pembumian_defect', 'bekalan_dua_defect', 'kaki_lima_defect', 'total_defects', 'planed_date', 'actual_date',
     'remarks', 'tapak_condition', 'kawasan', 'jarak_kelegaan', 'talian_spec', 'arus_pada_tiang' ,  'kawasan_other_img' , 'kawasan_forest_img' , 'kawasan_road_img' ,'kawasan_bend_img' , 'tapak_no_vehicle_entry_img'  , 'tapak_sidewalk_img' , 'tapak_road_img' ,'kaki_lima_defect_image' , 'bekalan_dua_defect_image ', 'Pembumian_defect_image' , 'servis_defect_image' , 'kilat_defect_image' , 'jumper_image' , 'blackbox_defect_image' , 'ipc_defect_image' , 'umbang_defect_image' , 'talian_defect_image'
     ,'tiang_defect_image' , 'talian_utama' ,'talian_utama_connection'];

}
