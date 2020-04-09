<?php 
namespace App\Models\Session;

use CodeIgniter\Model;

class SessionUserModel extends Model
{
    protected $table      = 'session_user';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = array(
        'session_id',
        'user_id',
        'is_remember', 
        'is_authenticated', 
        'is_active', 
        'is_on_activity'
    );

    public function get_active($token)
    {
        return $this->select(array(
                        'user_id AS id',
                        'user_id AS user_id',
                        'user_name AS user_name',
                        'user_profil.view_name AS user_view_name',
                        'user_profil.initial',
                        'session.id AS session_id',
                        'session_user.id AS session_user_id',
                        'token',
                        'is_active',
                        'is_remember',
                        'user_role_id',
                        'user_role.designation AS user_role',
                        'user_role.rules AS user_role_rules',
                    ))
                    ->where(array('session.token' => $token, 'session_user.is_active' => 1))
                    ->join('session', 'session.id=session_user.session_id', 'LEFT')
                    ->join('user', 'user.id=session_user.user_id', 'LEFT OUTER')
                    ->join('user_profil', 'user_profil.id=user.id', 'LEFT OUTER')
                    ->join('user_role', 'user.user_role_id=user_role.id', 'LEFT OUTER')
                    ->first();       
    }

    public function get_session_user($session_id, $user_id)
    {
        return $this->where(array('session_id' => $session_id, 'user_id' => $user_id))
                        ->first();
    }

    public function disable_session($session_id)
    {
        return $this->where('session_id', $session_id)
                ->set(array('is_active' => false))
                ->update();
    }

    public function enable_anonymous_session($session_id)
    {
        return $this->where(array('session_id' => $session_id, 'user_id' => null))
                ->set(array('is_active' => true))
                ->update();
    }

    public function enable_session($session_user_id)
    {
        return $this->where('id', $session_user_id)
                    ->set(array('is_active' => true))
                    ->update();       
    }
}