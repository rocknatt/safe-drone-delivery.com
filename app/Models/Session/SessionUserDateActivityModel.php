<?php 

namespace App\Models\Session;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class SessionUserDateActivityModel extends Model
{
    protected $table      = 'session_user_date_activity';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = array(
        'id',
        'session_user_id',
        'date_begin',
        'date_end'
    );

    public function new_fork($session_user_id)
    {
        helper('text');

        return $this->insert(array(
            'id' => random_string('alnum', 20),
            'session_user_id' => $session_user_id,
            'date_begin' => Time::now()->toDateTimeString(),
        ));
    }

    public function close_fork($session_user_id)
    {
        $this->where('session_user_id', $session_user_id)
            ->set('date_end', Time::now()->toDateTimeString())
            ->update();
    }

    public function has_expired($session_user_id)
    {
        $model = $this->where('session_user_id', $session_user_id)
                        ->orderBy('date_begin', 'DESC')
                        ->first();

        if ($model == null) {
            return false;
        }

        if ($model['date_end'] == null) {
            return false;
        }

        $date_begin = Time::parse($model['date_begin']);
        $date_end = Time::parse($model['date_end']);

        $diff = $date_begin->difference($date_end);

        return $diff->getMinutes() > 20;
    }
}