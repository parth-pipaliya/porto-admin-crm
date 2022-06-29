<?php
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
ini_set('memory_limit', -1);
/*
|--------------------------------------------------------------------------
| Image routes
|--------------------------------------------------------------------------
*/

Route::get('site/image', function () {
    abort(404);
});

Route::get('site/image/{folderName?}', function ($folderName) {
    abort(404);
});

Route::get('site/image/{folderName?}/{fileName?}', function ($folderName, $fileName) {
    if(Storage::disk('local')->exists('upload/images/'.$folderName.'/' . $fileName)){
        return Image::make(Storage::disk('local')->get('upload/images/'.$folderName.'/' . $fileName))->response();
    }
    abort(404);
});



?>
