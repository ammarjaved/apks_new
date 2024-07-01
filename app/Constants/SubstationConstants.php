<?php

namespace App\Constants;

class SubstationConstants
{
    public const PE_DEFECTS_AND_IMAGES = [
        'unlocked'                  =>  ['image_gate', 'images_gate_after_lock'],
        'demaged'                   =>  ['image_gate', 'substation_image_1'],
        'broken_roof'               =>  ['image_building', 'image_building_2'],
        'broken_gutter'             =>  ['image_building', 'image_building_2'],
        'broken_base'               =>  ['image_building', 'image_building_2'],
        'advertise_poster_status'   =>  ['image_advertisement_before_1', 'image_advertisement_after_1'],
        'tree_branches_status'      =>  ['image_tree_branches', 'image_tree_branches_2'],
        'grass_status'              =>  ['image_grass', 'image_grass_2'],
    ];

    public const PE_DEFECTS = [
        'unlocked',
        'demaged',
        'broken_roof',
        'broken_gutter',
        'broken_base',
        'advertise_poster_status',
        'tree_branches_status',
        'grass_status'
    ];

    public const PE_DEFECTS_DB_NAME = [
        'unlocked'                      => "gate_status->>'unlocked'",
        'demaged'                       => "gate_status->>'demaged'",
        'broken_roof'                   => "building_status->>'broken_roof'",
        'broken_gutter'                 => "building_status->>'broken_gutter'",
        'broken_base'                   => "building_status->>'broken_base'",
        'advertise_poster_status'       => "advertise_poster_status",
        'tree_branches_status'          => "tree_branches_status",
        'grass_status'                  => "grass_status"
    ];



}


?>
