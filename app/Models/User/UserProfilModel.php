<?php 
namespace App\Models\User;

use CodeIgniter\Model;
use App\Models\ImageModel;
use App\Models\User\UserModel;

class UserProfilModel extends Model
{
    protected $table      = 'user_profil';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = array(
        'name',
        'first_name',
        'view_name',
        'adress',
        'telephone',
        'email',
        'site_web',
        'cin',
        'nif',
        'stat',
        'rcs',
        'initial',
        'image_id',
        'user_category_id'
    );

    private $session;

    public function set_session($session)
    {
        $this->session = $session;
    }

    public function get_list($key = '', $order_by = 'view_name', $sort = 'desc', $nb = 10, $debut = 0, $user_category_id = null)
    {
        $data_list = $this->select(array(
                        'user.id', 
                        'user_name', 
                        'user_profil.email', 
                        'name', 
                        'first_name', 
                        'view_name', 
                        'is_blocked', 
                        'user_role_id', 
                        'user_role.designation AS user_role',
                        'user_category_id'
                    ))
                    ->like('lower(user_name)', strtolower($key))
                    ->orLike('lower(view_name)', strtolower($key))
                    ->join('user', 'user_profil.id=user.id', 'LEFT')
                    ->join('user_role', 'user.user_role_id=user_role.id', 'LEFT')
                    ->where('user_category_id', $user_category_id)
                    ->orderBy($order_by, $sort)
                    ->findAll($nb, $debut);

        if ($data_list == null) {
            return array();
        }

        //init accessibility properties
        foreach ($data_list as $key => $data) {
            $data_list[$key] = $this->write_accessibility($data);
        }

        return $data_list;
    }

    public function get_user_followed_list($debut = 0, $nb = 5)
    {
        // $this->config->load('chat');
        // $is_friend_required = $this->config->item('is_friend_required');

        // if ($is_friend_required) {
        //     //Todo : concevoir la requette d'ami et retourné les utilisateurs suivi ici
        // }else{

        // }

        //Todo : Utilisateur bloqué
        //Todo : seulement les utilisateur suivi
        //Todo : config for needing friendship
        //Todo : is_active, date_derniere_activity

        return $this->select('id, view_name')
                    ->where('id!=', $this->session->user_identity['id'])
                    ->findAll($nb, $debut);
    }

    public function get_client_list()
    {
        return $this->select('id, name, first_name, view_name, id AS value, view_name AS label')
                    ->where('user_category_id !=', null)
                    ->findAll();
    }

    public function get_view_metadata($user_category)
    {
        switch ($user_category) {
            //client
            case 1:
            //supplier
            case 2:
                return array(
                    'name',
                    'adress',
                    'telephone',
                    'email',
                    'site_web',
                    'nif',
                    'stat',
                    'rcs',
                );
                break;
            
            //user normal
            default:
                return array(
                    'name',
                    'first_name',
                    'adress',
                    'telephone',
                    'email',
                );
                break;
        }

        
    }

    public function write_accessibility($data)
    {
        $data['is_editable'] = $this->is_editable($data);
        $data['can_block'] = $this->can_block($data);
        $data['can_unblock'] = $this->can_unblock($data);
        $data['can_edit_image_profil'] = $this->can_edit_image_profil($data);

        $user_model = new UserModel();
        $user_model->set_session($this->session);
        $data['can_update_role'] = $user_model->can_update_role($data);
        $data['can_change_password'] = $user_model->can_change_password($data);
        $data['can_reset_password'] = $user_model->can_reset_password($data);

        return $data;
    }

    public function create($user_id, $data)
    {
        return $this->insert(array(
            'id' => $user_id,
            'name' => $data['name'],
            'first_name' => $data['first_name'],
            'view_name' => $data['name'] . ' ' . $data['first_name'],
            'email' => $data['email'],
            'adress' => $data['adress'],
            'telephone' => $data['telephone'],
            'site_web' => $data['site_web'],
            'cin' => $data['cin'],
            'nif' => $data['nif'],
            'stat' => $data['stat'],
            'rcs' => $data['rcs'],
            'user_category_id' => $data['user_category_id'],
        ));
    }

    public function get_user_profil($id)
    {
        $data = $this->select(array(
                        'user.id',
                        'user_name',
                        'user_profil.email',
                        'user.is_blocked',
                        'user_role_id',
                        'user_role.designation AS user_role',
                        'user_category_id',
                        'name',
                        'first_name',
                        'view_name',
                        'adress',
                        'telephone',
                        'site_web',
                        'cin',
                        'nif',
                        'stat',
                        'rcs',
                        'initial',
                    ))
                    ->join('user', 'user_profil.id=user.id', 'LEFT')
                    ->join('user_role', 'user.user_role_id=user_role.id', 'LEFT')
                    ->where('user_profil.id', $id)
                    ->orWhere('user.user_name', $id)
                    ->first();

        if ($data != null) {
            $data = $this->write_accessibility($data);
            $data['view_metadata'] = $this->get_view_metadata($data['user_category_id']);
        }

        return $data;
    }

    public function get_thumb($model)
    {
        if ($model == null) {
            return null;
        }

        $initial = $this->get_initial_from_name($model['name'], $model['first_name']);

        $filename = FOLDER_WRITABLE_PATH . '/base/img/user_bg.png';
        $image = imagecreatefrompng($filename); 

        $blanc = imagecolorallocate($image, 255, 255, 255);

        $font_path = FOLDER_WRITABLE_PATH . '/base/fonts/hind-regular.otf';

        // Tout d'abord, nous créons notre rectangle entourant notre premier texte
        $bbox = imagettfbbox(64, 0, $font_path, $initial);

        // Nos coordonnées en X et en Y
        $x = $bbox[0] + (imagesx($image) / 2) - ($bbox[4] / 2);
        $y = $bbox[1] + (imagesy($image) / 2) - ($bbox[5] / 2);

        imagettftext ($image , 64 , 0 , $x , $y , $blanc , $font_path , $initial );

        $thumb_file =  IMAGE_FOLDER_WRITABLE_PATH . '/user_' . $model['id'] . '.png';

        return imagepng($image, $thumb_file, 9);
    }

    public function get_image_profil($id, $image_id)
    {
        $image_model = new ImageModel();
        $image = $image_model->find($image_id);

        $adr = explode('.', $image['file_name']);
        $extension = isset($adr[1]) ? $adr[1] : '';
        $extension = strtolower($extension) == 'jpg' ? 'jpeg' : $extension;

        $filename= $image['file_name']; //<-- specify the image  file
        $filename = IMAGE_FOLDER_WRITABLE_PATH . '/'. $filename;
        $thumb_file = IMAGE_FOLDER_WRITABLE_PATH . '/user_' . $id . '.png';

        // Calcul des nouvelles dimensions
        list($width, $height) = getimagesize($filename);
        $new_width = 640;
        $new_height =(int)(($height * $new_width) / $width);
        $view_height = $new_width;//Pour le rognage afin d'obtenir une image carré

        // Redimensionnement
        $image_p = imagecreatetruecolor($new_width, $view_height);
        if ($extension == 'png') {
            $image = imagecreatefrompng($filename);
        }else if ($extension == 'jpeg') {
            $image = imagecreatefromjpeg($filename);
        }
        
        //Transparence
        $transparencyIndex = imagecolortransparent($image); 
        $transparencyColor = array('red' => 255, 'green' => 255, 'blue' => 255); 
         
        if ($transparencyIndex >= 0) { 
            $transparencyColor    = imagecolorsforindex($image, $transparencyIndex);    
        } 
        
        $transparencyIndex = imagecolorallocate($image_p, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']); 
        imagefill($image_p, 0, 0, $transparencyIndex); 
        imagecolortransparent($image_p, $transparencyIndex); 
        //Redimensionnement
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        // Affichage
        if ($extension == 'png'){
            return imagepng($image_p, $thumb_file, 9);
        }else if ($extension == 'jpeg') {
            return imagejpeg($image_p, $thumb_file, 100);
        }
    }

    private function get_initial_from_name($name, $first_name)
    {
        return $this->get_first_letter($name) . '' . $this->get_first_letter($first_name);
    }

    public function get_client_form($user_category_id)
    {
        return array(
            'title' => lang('STD.std_add_account_' . $user_category_id),
            'data_list' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'user_category_id',
                    'value' => $user_category_id,
                ),
                array(
                    'type' => 'text',
                    'name' => 'user_name',
                    'id' => 'user_name',
                    'label' => lang('STD.std_name'),
                    'is_required' => true,
                    'placeholder' => lang('STD.std_name'),
                ),
                array(
                    'type' => 'text',
                    'name' => 'email',
                    'id' => 'email',
                    'label' => lang('STD.std_email'),
                    'is_required' => true,
                    'placeholder' => lang('STD.std_email'),
                ),
                array(
                    'type' => 'text',
                    'name' => 'telephone',
                    'id' => 'telephone',
                    'label' => lang('STD.std_phone'),
                    'is_required' => true,
                    'placeholder' => lang('STD.std_phone'),
                ),
                array(
                    'type' => 'text',
                    'name' => 'adress',
                    'id' => 'adress',
                    'label' => lang('STD.std_adress'),
                    'placeholder' => lang('STD.std_adress'),
                ),
                array(
                    'type' => 'text',
                    'name' => 'site_web',
                    'id' => 'site_web',
                    'label' => lang('STD.std_website'),
                    'placeholder' => lang('STD.std_website'),
                ),
                array(
                    'type' => 'text',
                    'name' => 'nif',
                    'id' => 'nif',
                    'label' => lang('STD.std_nif'),
                    'placeholder' => lang('STD.std_nif'),
                ),
                array(
                    'type' => 'text',
                    'name' => 'stat',
                    'id' => 'stat',
                    'label' => lang('STD.std_stat'),
                    'is_autosize' => true,
                    'placeholder' => lang('STD.std_stat'),
                ),
                array(
                    'type' => 'text',
                    'name' => 'rcs',
                    'id' => 'rcs',
                    'label' => lang('STD.std_rcs'),
                    'is_autosize' => true,
                    'placeholder' => lang('STD.std_rcs'),
                ),
            ),
        );
    }

    private function get_first_letter($str)
    {
        if ($str == '') {
            return '';
        }

        return substr(strtoupper($str), 0, 1);
    }

    public function can_edit_image_profil($user)
    {
        return $this->is_editable($user);
    }

    public function can_block($user)
    {
        //cannot block superadmin
        if ($user['user_role_id'] == 2) {
            return false;
        }

        //cannot block himself
        if ($user['id'] == $this->session->user_identity['id']) {
            return false;
        }

        if (!$user['is_blocked'] && $this->session->is_superadmin()) {
            return true;
        }

        return !$user['is_blocked'] && $this->session->is_authorized('user', 'block', true);
    }

    public function can_unblock($user)
    {
        //cannot block superadmin
        if ($user['user_role_id'] == 2) {
            return false;
        }

        //cannot block himself
        if ($user['id'] == $this->session->user_identity['id']) {
            return false;
        }

        if ($user['is_blocked'] && $this->session->is_superadmin()) {
            return true;
        }

        return $user['is_blocked'] && $this->session->is_authorized('user', 'block', true);
    }

    public function can_create_user()
    {
        if ($this->session->is_superadmin()) {
            return true;
        }

        return $this->session->is_authorized('user', 'create', true);
    }

    public function is_editable($user)
    {
        if ($user == null) {
            return false;
        }

        if ($this->session->is_superadmin()) {
            return true;
        }

        if ($user['id'] == $this->session->user_identity['id']) {
            return true;
        }

        return $this->session->is_authorized('user', 'update', true);
    }
}