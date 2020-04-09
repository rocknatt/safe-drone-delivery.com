<?php 
namespace App\Models\User;

use CodeIgniter\Model;
use App\Models\User\UserNotificationParamModel;
use App\Models\Mzara\LoveEntityModel;
use App\Models\HubModel;
use App\Models\User\UserProfilModel;

class UserNotificationModel extends Model
{
    protected $table      = 'user_notification';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = array(
        'id',
        'user_id',
        'message',
        'link',
        'object_id',
        'object_type',
        'object_occurence',
        'date_read'
    );

    protected $useTimestamps = true;

    private $session;

    public function set_session($session)
    {
        $this->session = $session;
    }

    public function create($title, $message, $link, $object_type, $object_id, $user_id = null)
    {
        //can't leave self notification
        if ($user_id == $this->session->user_identity['id']) {
            return 0;
        }

        $model = $this->where(array(
                            'object_type' => $object_type,
                            'object_id' => $object_id,
                            'user_id' => $user_id,
                            'date_read' => null,
                        ))
                        ->first();

        $id = '';

        if ($model == null) {
            helper('text');

            $id = random_string('alnum', 20);
            $this->insert(array(
                'id' => $id,
                'titre' => $title,
                'message' => $message,
                'link' => $link,
                'user_id' => $user_id,
                'object_id' => $object_id,
                'object_type' => $object_type,
                'object_occurence' => 1
            ));
        }

        if ($model != null) {
            $id = $model['id'];
            $this->where('id', $model['id'])
                ->set(array(
                    'message' => $message,
                    'link' => $link,
                    'date_read' => null,
                    'object_occurence' => $model['object_occurence'] + 1
                ))
                ->update();
        }

        $user_notification_param_model = new UserNotificationParamModel();
        $count = $user_notification_param_model->increment_notification_rip($user_id);

        $icon = site_url('assets/img/logo.png');
        $tag = uniqid('not_');

        //nn: notification_new

        $hub = new HubModel();
        $hub->push_message(
            'not_new', 
            array(
                'id' => $id,
                'title' => lang('STD.' . $title),
                'message' => $this->get_view_message($message),
                'icon' => $icon,
                'url' => $link,
                'tag' => $tag,
                'id' => $object_id,
                'type' => $object_type,
            ), 
            $user_id
        );

        return $id;
    }

    public function create_love_notification($entity_type, $entity_id, $user_to_notify_list, $not_link, $show_user_concerned = true)
    {
        $love_model = new LoveEntityModel();

        $user_list = $love_model->where('entity_type', $entity_type)
                                ->where('entity_id', $entity_id)
                                ->orderBy('created_at', 'desc')
                                ->findAll(4, 0);
        $user_love_count = $love_model->where('entity_type', $entity_type)
                                    ->where('entity_id', $entity_id)
                                    ->countAll();

        $not_title = $entity_type;
        $not_message = array();

        foreach ($user_list as $index => $user) {
            if ($index < 3) {
                $not_message['user_id_'. $user['user_id']] = $user['user_id'];
                if ($index < count($user_list) - 1) {
                    $not_message['text_u_'. $user['user_id']] = ',';
                }
            }
            else{
                $not_message['text_1'] = $user_love_count . ' ';
                $not_message['lang_1'] = 'std_more_person';
                $not_message['text_2'] = '... ';
            }
        }

        $not_message['lang_2'] = 'std_loved_your';
        $not_message['lang_3'] = 'std_' . $entity_type;
        $not_message['text_p'] = '.';

        foreach ($user_to_notify_list as $user_id) {
            $this->create(
                $not_title,
                json_encode($not_message),
                $not_link,
                $entity_type,
                $entity_id,
                $user_id
            );
        }
    }

    public function get_view_message($message)
    {
        $str = '';
        $adr_list = json_decode($message, true);
        $user_profil_model = new UserProfilModel();
        //Todo : check if class exist
        $shop_model = new \App\Models\Mzara\Shop\ShopModel();

        foreach ($adr_list as $key => $value) {
            if (strstr($key, 'user_id')) {
                $user = $user_profil_model->find($value);

                $str .= ' <b>' . $user['view_name'] . '</b>';
                $str .= ' ';
            }

            if (strstr($key, 'shop_id')) {
                $shop = $shop_model->find($value);

                $str .= ' <b>' . $shop['name'] . '</b>';
                $str .= ' ';
            }

            if (strstr($key, 'lang')) {
                $str .= lang('STD.' . $value);
                $str .= ' ';
            }

            if (strstr($key, 'text')) {
                $str .= $value;
            }
        }

        return $str;
    }

    public function write($message, $user_id)
    {
        
    }
}