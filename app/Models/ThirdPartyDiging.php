<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ThirdPartyDiging extends Model
{
    use HasFactory;
    public $table = 'tbl_third_party_diging_patroling';
    protected $fillable = ['id', 'wp_name', 'zone', 'ba', 'team_name', 'survey_date', 'patrolling_time', 'road_id','project_name', 'feeder_involved',
    'km_plan', 'km_actual', 'digging, notice', 'supervision', 'company_name', 'office_phone_no', 'main_contractor', 'developer_phone_no',
    'contractor_company_name', 'site_supervisor_name', 'site_supervisor_phone_no', 'excavator_operator_name','excavator_machinery_reg_no',
    'updated_at', 'created_at', 'workpackage_id', 'department_diging','survey_status','before_image1', 'before_image2', 'before_image3','geom',
    'during_image1','during_image2','during_image3','after_image1','during_image2','during_image3','digging_notice' ];

    public function wpData() {
        return $this->belongsTo(WorkPackage::class, 'workpackage_id');
    }
}
