<?php

namespace App\Service;

class UploadImageBase64
{
    public function UploadImage($picture, $dir){
        if(preg_match("#^data:image\/(?<extension>(?:png|gif|jpg|jpeg));base64,(?<image>.+)$#", $picture, $matchings)){
            $extension = $matchings['extension'];
            $imageData = base64_decode($matchings['image']);
            $basename=uniqid();
            $file = $dir . $basename .'.'.$extension;

            if (file_put_contents($file, $imageData)) {
                return $basename.'.'.$extension;
            } else {
                return false;
            }
        }
    }
}
