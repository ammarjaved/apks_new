<?php

namespace App\Constants;

class TiangConstants
{
    public const TIANG_DEFECT = [
        'tiang_defect'         => ['cracked', 'leaning', 'dim', 'creepers', 'other'],
        'talian_defect'        => ['joint', 'need_rentis', 'ground', 'other','talian_sbum'],
        'umbang_defect'        => ['breaking', 'creepers', 'cracked', 'stay_palte', 'other'],
        'ipc_defect'           => ['burn','ipc_n_krg2','ec_tiada','other'],
        'blackbox_defect'      => ['cracked', 'other'],
        'jumper'               => ['sleeve', 'burn', 'other'],
        'kilat_defect'         => ['broken', 'other'],
        'servis_defect'        => ['roof', 'won_piece', 'other'],
        'pembumian_defect'     => ['netural', 'other'],
        'bekalan_dua_defect'   => ['damage', 'other'],
        'kaki_lima_defect'     => ['date_wire', 'burn','usikan_pengguna','other'],
        'tapak_condition'      => ['road', 'side_walk', 'vehicle_entry'],
        'kawasan'              => ['road', 'bend', 'forest', 'other']
    ];

    public const TIANG_IMAGES = ['pole_image_1', 'pole_image_2', 'pole_image_3', 'pole_image_4', 'pole_image_5','current_leakage_image' , 'remove_creepers_image' , 'clean_banner_image'];


    public const TIANG_DEFECTS_KEYS =[
        'tiang_cracked', 'tiang_leaning', 'tiang_dim', 'tiang_creepers',
        'talian_joint', 'talian_need_rentis', 'talian_ground',
        'umbang_breaking', 'umbang_creepers', 'umbang_cracked', 'umbang_stay_palte',
        'ipc_burn',
        'blackbox_cracked',
        'jumper_sleeve', 'jumper_burn',
        'kilat_broken',
        'servis_roof', 'servis_won_piece',
        'pembumian_netural',
        'bekalan_dua_damage',
        'kaki_lima_date_wire', 'kaki_lima_burn',
        'tapak_condition_road', 'tapak_condition_side_walk', 'tapak_condition_vehicle_entry',
        'kawasan_road', 'kawasan_bend', 'kawasan_forest',
    ];


    public const TIANG_DEFECTS_DB_NAME = [
        'tiang_cracked'                     =>"tiang_defect->>'cracked'",
        'tiang_leaning'                     =>"tiang_defect->>'leaning'",
        'tiang_dim'                         =>"tiang_defect->>'dim'",
        'tiang_creepers'                    =>"tiang_defect->>'creepers'",
        'talian_joint'                      =>"talian_defect->>'joint'",
        'talian_need_rentis'                =>"talian_defect->>'need_rentis'",
        'talian_ground'                     =>"talian_defect->>'ground'",
        'umbang_breaking'                   =>"umbang_defect->>'breaking'",
        'umbang_creepers'                   =>"umbang_defect->>'creepers'",
        'umbang_cracked'                    =>"umbang_defect->>'cracked'",
        'umbang_stay_palte'                 =>"umbang_defect->>'stay_palte'",
        'ipc_burn'                          =>"ipc_defect->>'burn'",
        'blackbox_cracked'                  =>"blackbox_defect->>'cracked'",
        'jumper_sleeve'                     =>"jumper->>'sleeve'",
        'jumper_burn'                       =>"jumper->>'burn'",
        'kilat_broken'                      =>"kilat_defect->>'broken'",
        'servis_roof'                       =>"servis_defect->>'roof'",
        'servis_won_piece'                  =>"servis_defect->>'won_piece'",
        'pembumian_netural'                 =>"pembumian_defecct->>'netural'",
        'bekalan_dua_damage'                =>"bekalan_dua_defect->>'damage'",
        'kaki_lima_date_wire'               =>"kaki_lima_defect->>'date_wire'",
        'kaki_lima_burn'                    =>"kaki_lima_defect->>'burn'",
        'tapak_condition_road'              =>"tapak_condition::json->>'road'",
        'tapak_condition_side_walk'         =>"tapak_condition::json->>'side_walk'",
        'tapak_condition_vehicle_entry'     =>"tapak_condition::json->>'vehicle_entry'",
        'kawasan_road'                      =>"kawasan::json->>'road'",
        'kawasan_bend'                      =>"kawasan::json->>'bend'",
        'kawasan_forest'                    =>"kawasan::json->>'forest'",
    ];


}


?>
