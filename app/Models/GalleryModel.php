<?php 
namespace App\Models;

use CodeIgniter\Model;

class GalleryModel extends Model
{
    protected $table      = 'gallery';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = array(
        'name',
        'user_id',
        'comments',
    );

    protected $useTimestamps = true;

    public static $default_profil_picture_name = 'default:STD.std_profil_picture';
    public static $default_cover_picture_name = 'default:STD.std_cover_picture';
}