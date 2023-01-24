<?php    
if (! function_exists('image_path')) {

    function image_path($value, $default = 1) 
    {
        if (is_object($value)) {
            return is_null($value)
                ? ( is_null($default) ? $value : asset("/img/no-image.jpg")) 
                : Storage::url($value->path);
        }

        return is_null($value)
            ? (is_null($default) ? $value : asset("/img/no-image.jpg"))
            : Storage::url($value);
    }
}

/**
 * File upload.
 */
if (! function_exists('get_uploaded_file')) {

    function get_uploaded_file($file, $path) 
    {
        $fileName = time().\Str::random(6).'.'.$file->getClientOriginalExtension();

        return $file->storeAs($path, $fileName);
    }   
}