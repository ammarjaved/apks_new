<?php

namespace App\Constants;

class FeederPillarConstants
{
    public const FP_DEFECTS_AND_IMAGES = [
        'unlocked'                  =>  ['image_gate', 'images_gate_after_lock'],
        'demaged'                   =>  ['image_gate', 'image_gate_2'],
        'vandalism_status'          =>  ['image_vandalism', 'image_vandalism_2'],
        'leaning_staus'             =>  ['image_leaning', 'image_leaning_2'],
        'rust_status'               =>  ['image_rust', 'image_rust_2'],  
        'fp_gaurd'                  =>  ['image_name_plate', 'feeder_pillar_image_1'],
        'paint_status'              =>  ['feeder_pillar_image_1', 'feeder_pillar_image_2'],
        'advertise_poster_status'   =>  ['image_advertisement_before_1', 'image_advertisement_after_1'],
    ];

    public const FP_DEFECTS = [
        'unlocked',
        'demaged',
        'vandalism_status',
        'leaning_staus',
        'rust_status',
        'fp_gaurd',
        'paint_status',
        'advertise_poster_status',
    ];

    public const FP_DEFECTS_DB_NAME = [
        'unlocked'                      => "gate_status->>'unlocked'",
        'demaged'                       => "gate_status->>'demaged'",
        'vandalism_status'              => "vandalism_status",
        'leaning_staus'                 => "leaning_staus",
        'rust_status'                   => "rust_status",
        'fp_gaurd'                      => "fp_gaurd",
        'paint_status'                  => "paint_status",
        'advertise_poster_status'       => "advertise_poster_status",

    ];
 


}


?>
