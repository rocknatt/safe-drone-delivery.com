<?php 
namespace App\Models;

use CodeIgniter\Model;
use \CodeIgniter\Files\File;

class ImageModel extends Model
{
    protected $table      = 'image';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = array(
        'file_name',
        'comments',
        'gallery_id',
    );

    protected $useTimestamps = true;

    public function add($file, $gallery_id = null)
    {
        if ($file == null) {
            return null;
        }

        if (! $file->isValid())
        {
            return null;
        }

        $type = $file->getClientMimeType();
        //Only image accepted
        if (!($type == 'image/png' || $type == 'image/jpeg')) {
            return null;
        }

        $file_name = $file->getName();
        $file->move(IMAGE_FOLDER_WRITABLE_PATH);

        $image_id = $this->insert(array(
            'file_name' => $file_name,
            'gallery_id' => $gallery_id,
        ));

        return $image_id;
    }

    public function _delete($id)
    {
        //Todo : make decision, when removing photos, delete physical file or let them stay there

        return $this->delete($id);
    }

    public function write_image($image_id, $new_width = null, $prefix = '')
    {
        $image = $this->find($image_id);
        $file_path = IMAGE_FOLDER_WRITABLE_PATH . '/' . $image['file_name'];

        $file = new File($file_path);

        $mime = $file->getMimeType();
        $filesize = $file->getSize();
        $extension = $file->guessExtension();
        $file_name = $image['file_name'];
        $thumb_file = IMAGE_FOLDER_WRITABLE_PATH . '/' . $prefix . '_' . $image_id . '.' . $extension;

        /**
         * calculate new dimension
         */
        list($width, $height) = getimagesize($file_path);
        $new_width = $new_width == null ? $width : $new_width;
        $new_height =(int)(($height * $new_width) / $width);

        /**
         * create image object
         */
        $image_p = imagecreatetruecolor($new_width, $new_height);
        if ($extension == 'png') {
            $image = imagecreatefrompng($file_path);
        }else if ($extension == 'jpeg' || $extension == 'jpg') {
            $image = imagecreatefromjpeg($file_path);
        }

        /**
         * transparency
         */
        imagealphablending($image_p, false);
        imagesavealpha($image_p, true);
        $transparent = imagecolorallocatealpha($image_p, 255, 255, 255, 127);
        imagefilledrectangle($image_p, 0, 0, $width, $height, $transparent);

        /**
         * apply new dimension
         */
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        // Affichage
        if ($extension == 'png'){
            imagepng($image_p, $thumb_file, 9);
        }else if ($extension == 'jpeg' || $extension == 'jpg') {
            imagejpeg($image_p, $thumb_file, 100);
        }
    }
}