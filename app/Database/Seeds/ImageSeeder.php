<?php 

namespace App\Database\Seeds;
use CodeIgniter\I18n\Time;

class ImageSeeder extends BaseSeeder
{

    public function run()
    {
        // nothing to write here, this is just a base class
    }

    public function get_folder_image_writable()
    {
        $folder_path = dirname(FCPATH);
        $folder_path = dirname($folder_path);// outside mzara-folder

        return $folder_path . '/safe-dron-delivery-files/img/';
    }

    protected function path($str)
    {
        $path = dirname(FCPATH);
        return $path . '/app/Files/' . $str;
    }

    protected function _random_image_profil($type = 'profil', $extra = null)
    {
        $path = $this->path('Image/');

        switch ($type) {
            case 'profil':
                $adr = glob($path . 'Profil/*');
                return $this->pickOne($adr);

            case 'cover':
                $adr = glob($path . 'Cover/*');
                return $this->pickOne($adr);

            case 'product':
                $adr = glob($path . 'Product/' . $extra . '/*');
                return $this->pickOne($adr);
            
            default:
                $adr = glob($path . 'Profil/*');
                return $this->pickOne($adr);
        }

    }

    public function get_gallery($user_id = null)
    {
        $this->db->table('gallery')->insert(array(
            'created_at' => Time::now()->toDateTimeString(),
            'user_id' => $user_id,
            'name' => $this->generateString(true),
        ));

        $last = $this->db->table('gallery')
                                ->orderBy('id', 'desc')
                                ->get(1, 0)
                                ->getResult('array');

        return $last[0]['id'];
    }

    public function get_random_image($gallery_id = null, $type = 'profil', $extra = null)
    {
        $image_path = $this->_random_image_profil($type, $extra);

        $dest_path = $this->get_folder_image_writable();
        $dest_path = $dest_path . basename($image_path);

        //moving file
        if (!file_exists($dest_path)) {
            copy($image_path, $dest_path);
        }

        $this->db->table('image')->insert(array(
            'created_at' => Time::now()->toDateTimeString(),
            'file_name' => basename($image_path),
            'gallery_id' => $gallery_id,
        ));

        $last = $this->db->table('image')
                                ->orderBy('id', 'desc')
                                ->get(1, 0)
                                ->getResult('array');

        return $last[0]['id'];
    }

}