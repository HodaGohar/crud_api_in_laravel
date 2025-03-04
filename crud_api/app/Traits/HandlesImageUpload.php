<?php

namespace App\Traits;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;

trait HandlesImageUpload  {
     public function uploadImage(Request $request , $fileName = 'image' , $folder = 'students'){
         if($request->hasFile($fileName)){
            return $request->file($fileName)->store($folder , 'public');
         }
         return null;
     }

     public function deleteImage($path){
        if($path){
            Storage::disk('public')->delete($path);
        }
     }
}
