<?php

namespace App\Http\traits;

trait media
{
    public function uploadPhoto($image, $folder)
    {
        //upload photo 
        $photoName = uniqid() . '.' . $image->extension();
        $image->move(public_path('/dist/img/' . $folder), $photoName);
        return $photoName;
    }

    public function deletePhoto($photoPath)
    {
        if (file_exists($photoPath)) {
            unlink($photoPath);
            return true;
        }
        return false;
    }
}
