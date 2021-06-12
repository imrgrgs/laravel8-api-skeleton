<?php

namespace App\Traits;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;

trait Images
{
    /**
     * Save an Image
     * @param File $image
     * @param [type] $id
     * @param array $options key => value array for resize image ['width'=> 000, 'height' => 000]
     * @return array contains original name from image and name saved fro image ['imageOriginalName' => 'nameoriginal.jpeg', 'imageNameSaved' => 'yyyy-mm-dd4546165654hkdhkkdskf.jpeg']
     */
    private function loadImage($image, $storage, $options = [])
    {
        if (!in_array(strtolower($image->getClientOriginalExtension()), ['jpg', 'gif', 'png', 'jpeg', 'svg'])) {
            throw new Exception('invalid image', Response::HTTP_BAD_REQUEST);
        }

        $extension = $image->getClientOriginalExtension(); // getting image extension
        $size = $image->getSize();
        $mimeType = $image->getMimeType();
        $date = Carbon::now()->format('Y-m-d');
        // for save original image

        $imageOriginalName = $image->getClientOriginalName();
        if (!empty($options['nameToSave'])) {
            $imageNameSaved  = $options['nameToSave'] . '.' . $extension;
        } else {
            $imageNameSaved  = $date . '_' . uniqid() . '.' . $extension;
        }

        Storage::putFileAs($storage, $image, $imageNameSaved, 'public');

        $height = Image::make($image)->height();
        $width = Image::make($image)->width();

        // for store resized image
        if (!empty($options['width']) && !empty($options['height'])) {
            $oAvatar = Image::make($image->getRealPath())->fit($options['width'], $options['height']);
            $oAvatar = $oAvatar->stream();
            if (!empty($options['storage_thumb'])) {
                $storage = $options['storage_thumb'];
            } else {
                $storage = $storage . '/thumb';
            }
            Storage::put($storage . DIRECTORY_SEPARATOR . $imageNameSaved, $oAvatar->__toString());
        }

        return [
            'path' => $storage,
            'originalName' => $imageOriginalName,
            'nameSaved' => $imageNameSaved,
            'extension' => $extension,
            'size' => $size,
            'height' => $height,
            'width' => $width,
            'mimeType' => $mimeType,
        ];
    }

    private function deleteImage($image, $storage)
    {
        try {
            unlink(public_path() . Storage::url($storage) . '/' . $image);
        } catch (\Throwable $th) {
            return false;
        }
        return  true;
    }
}
