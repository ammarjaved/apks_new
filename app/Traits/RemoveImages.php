<?php

namespace App\Traits;


trait RemoveImages
{

    public function removeImages($images , $array){

        foreach($images as $image){
            if (!empty($array[$image]) && file_exists(public_path($array[$image]))) {
                unlink(public_path($array[$image]));
            }
        }
    }

}


?>
