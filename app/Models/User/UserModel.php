<?php 

namespace App\Models\User;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'user';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = array(
        'user_name',
        'user_name_tolower',
        'email',
        'email_tolower',
        'password', 
        'uniq_id_reset_pass', 
        'date_ajout_uniq_id_reset_pass', 
        'date_dernier_modification_password',
        'is_blocked',
        'user_role_id'
    );

    protected $useTimestamps = false;
    protected $createdField  = 'date_inscription';
    protected $updatedField  = 'updated_at';

    private $session;

    public function set_session($session)
    {
        $this->session = $session;
    }

    public function create($user_name, $email, $password)
    {
        return $this->insert(array(
            'user_name' => $user_name,
            'user_name_tolower' => mb_strtolower($user_name),
            'email' => $email,
            'email_tolower' => mb_strtolower($email),
            'password' => hash('md5', $password),
            'user_role_id' => 4
        ));
    }

    public function auth($user_name, $password)
    {
        
        $user = $this->find_user($user_name);

        $result = array();
        $result['user'] = $user;

        if ($user['is_blocked']) {
            $result['status'] = 'auth_blocked';
        }

        if ($this->validate_credential($user['password'], $password)) {
            $result['status'] = 'auth_ok';
        }
        else{
            $result['status'] = 'auth_fail';
        }

        return $result;
    }

    public function find_user($user_name)
    {
        //find by email
        $user = $this->where('lower(email)', mb_strtolower($user_name))
                    ->first();

        if ($user == null) {
            //find by phone number
            $user = $this->find($user_name);
        }

        if ($user == null) {
            //find by user_name
            $user = $this->where('lower(user_name)', mb_strtolower($user_name))
                        ->first();
        }

        if ($user == null) {
            //find by phone number
            $user = $this->join('user_profil', 'user_profil.id=user.id', 'LEFT')
                        ->like('lower(user_profil.telephone)', mb_strtolower($user_name))
                        ->first();
        }

        //not avalaible for now    
        // if ($user == null) {
        //     //find by phone_number
        //     $user = $this->where('lower(user_name)', mb_strtolower($user_name))
        //                 ->first();
        // }

        return $user;
    }

    public function validate_credential($internal_password, $password)
    {
        return $internal_password === hash('md5', $password);
    }

    public static function get_role()
    {
        return array(
            'label' => 'std_user',
            'value' => 'user', 
            'data_list' => array(
                array('label' => lang('STD.std_role'), 'value' => 'user_role'),
                array('label' => lang('STD.std_password'), 'value' => 'password'),
                array('label' => lang('STD.std_reset_password'), 'value' => 'reset_password'),
                array('label' => lang('STD.std_disable'), 'value' => 'block'),
                array('label' => lang('STD.std_update'), 'value' => 'update'),
            )
        );
    }

    private function get_phone_number_clean($str)
    {
        return trim(
            str_replace(' ', '', $str)
        );
    }

    public function get_escaped_user_name($user_name)
    {
        $_user_name = str_replace(' ', '.', $user_name);
        $_user = $this->where('lower(user_name)', mb_strtolower($user_name))->first();
        // user_name already used
        if ($_user != null) {

            $last_number = $this->like('lower(user_name)', mb_strtolower($user_name))
                ->countAllResults();
            $_user_name .= '.'. $last_number;
        }

        return mb_strtolower($_user_name);
    }

    public function can_update_role($user)
    {
        if ($user == null) {
            return false;
        }

        if ($user['user_role_id'] == 2) {
            return false;
        }

        if ($this->session->is_superadmin()) {
            return true;
        }

        return $this->session->is_authorized('user', 'user_role', true);
    }

    public function can_change_password($user)
    {
        if ($user == null) {
            return false;
        }

        if ($this->session->is_superadmin()) {
            return true;
        }

        if ($this->session->is_authorized('user', 'password', true)) {
            return true;
        }

        return $user['id'] == $this->session->user_identity['id'];
    }

    public function can_reset_password($user)
    {
        if ($user == null) {
            return false;
        }

        if ($user['user_role_id'] == 2) {
            return false;
        }

        if ($this->session->is_superadmin()) {
            return true;
        }

        return $this->session->is_authorized('user', 'reset_password', true);
    }

    public function can_toggle_block($user)
    {
        //cannot block superadmin
        if ($user['user_role_id'] == 2) {
            return false;
        }

        //cannot block himself
        if ($user['id'] == $this->session->user_identity['id']) {
            return false;
        }

        if ($this->session->is_superadmin()) {
            return true;
        }

        return $this->session->is_authorized('user', 'block', true);
    }
}