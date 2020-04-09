<?php 
namespace App\Models\User;

use CodeIgniter\Model;
use App\Models\HubModel;

class UserNotificationParamModel extends Model
{
    protected $table      = 'user_notification_param';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = array(
        'user_id',
        'unread_nb',
        'param',
        'sound',
        'external',
        'email',
        'sms'
    );

    public function _find($id)
    {
        $param = $this->where('user_id', $id)
                        ->first();

        if ($param == null) {
            $this->insert(array(
                'user_id' => $id,
                'param' => ''
            ));

            return $this->_find($id);
        }

        return $param;
    }

    public function increment_rip($user_id, $reset = false)
    {
        $unread_nb = 0;
        $$chat_user_sender_list = array();

        if (!$reset) {
            $adr = $this->select('unread_nb')
                    ->where(array('user_id' => $user_id))
                    ->first();

            $unread_nb = $adr['unread_nb'] + 1;
        }
        
        $this->where('user_id', $user_id)
            ->set('unread_nb', $unread_nb)
            ->update();

        //prevent hub
        $hub = new HubModel();
        $hub->push_message('not_rip', $unread_nb, $user_id);

        return $unread_nb;

    }

}