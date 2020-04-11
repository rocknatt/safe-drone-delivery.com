<?php 

namespace App\Models\Session;

use CodeIgniter\Model;
use App\Models\Session\SessionUserModel;
use App\Models\Session\SessionUserDateActivityModel;
use App\Models\User\UserRoleModel;

class SessionModel extends Model
{
    protected $table      = 'session';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = array(
    	'token',
    	'browser',
		'browser_version',
		'mobile_name',
		'robot_name',
		'referer',
		'user_agent',
		'plateform',
    );

    protected $useTimestamps = true;

    public $user_identity;
    public $lang;
    private $user_token;
    public $cookie_token_index = 'id';
    public $cookie_lang_index = 'lang';

    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
	{
		parent::__construct($db, $validation);

		//init_user_identity
		$this->user_identity = $this->get_user_identity();
	}

	public function get_user_identity()
	{
		helper('cookie');
		helper('text');
		$session_user = null;
		$token = get_cookie($this->cookie_token_index, true);
		$lang = get_cookie($this->cookie_lang_index, true);

		$request = \Config\Services::request();
		$agent = $request->getUserAgent();

		//exception dev for rest api
		if (ENVIRONMENT !== 'production')
		{
			$browser = $agent->getBrowser();
			$lang = 'fr';
			//Exception Dev
			if (strstr($browser, 'Edge')) {
				$session = $this->base->find('session', 131356);
			}
			if (strstr($browser, 'Opera')) {
				$token = '5b5cc1064128e1';
			}
			if (strstr($browser, 'Chrome') || strstr($browser, 'Safari')) {
				$token = '5b5cc0d13eb5e7';
			}
			if (strstr($browser, 'Mozilla') || strstr($browser, 'Firefox')) {
				$token = '5b6994bc41ded6';
			}
		}

		// token of Task.php
		if ($token == '5ca30d2fb468f') {
			return $this->get_task_default_session('5ca30d2fb468f');
		}
		// check if client accept cookie
		if ($token == null && !isset($_GET['id'])) {
			$token = random_string('alnum', 20);

			// cookie set to be one year
			setcookie($this->cookie_token_index, $token, strtotime('last day of december this year'), '/');
			header('Location: ' . site_url(uri_string()) . '?id=' . $token);
			die();
			exit();
		}
		// client doesn't accept cookie
		else if($token == null && (isset($_GET['id']) || (isset($_GET['c']) && $_GET['c'] == 1) )){

			//Todo : set does not accept cookie inside GET query


			//Todo : if robot, save robot name and create session, if session already exist, reuse it

			if ($agent->isRobot()) {
				$session = $this->where(array('robot_name' => $agent->getRobot()))->first();

				if ($session == null) {
					$token = random_string('alnum', 20);
					$session_user = $this->get_session_user($token);
				}
				else{
					$session_user = $this->get_session_user($session->token);
				}
			}
			// else{
			// 	//todo : Trouver un moyen de connaitre l'utilisateur même si il est anonyme
			// 	//1 - Ne pas enregistrer à tout bout de champs qui va fausser les données
			// 	//2 - Ne pas faire une session commune qui va unifier les données et qui ne va pas determiner le nombre d'utilisateur actif simultanée
			// 	//3 - Le système est stable si on suppose que les utilisateur, sauf les robots, acceptent les données
			// 	$session_user = $this->get_default_session($_GET['id']);
			// }
		}
		// client do accept cookie
		else if($token != null){

			// Todo : set accept cookie (clear GET query)
			// set_does_not_accept_cookie(0);
			$session_user = $this->get_session_user($token);
		}

		//check if session expired
		if ($session_user != null && $session_user['user_id'] != null && $this->session_has_expired($session_user)) {
			$this->logout_device($session_user['session_id']);
			$session_user = $this->get_session_user($token);
		}

		return $session_user;
	}

	public function get_device_list($key, $order_by, $sort, $nb, $debut)
	{
		return $this->select('id, browser, browser_version, mobile_name, robot_name, plateform')
                    ->like('lower(browser)', strtolower($key))
                    ->orLike('lower(mobile_name)', strtolower($key))
                    ->orLike('lower(robot_name)', strtolower($key))
                    ->orLike('lower(plateform)', strtolower($key))
                    ->orderBy($order_by, $sort)
                    ->findAll($nb, $debut);
	}

	public function get_session_user($token)
	{
		$session_user_model = new SessionUserModel();

		//find session device
		$session = $session_user_model->get_active($token);
		if ($session == null) {
			//save device
			$session_id = $this->save_new_device($token);
			$this->create_session_user($session_id);

			return $this->get_session_user($token);
		}

		return $session;
	}

	public function get_task_default_session($token)
	{
		return array(
			'token' => $token,
			'id' => 0,
			'user_name' => 'task',
			'email' => 'task@na.na',
			'user_role_id' => 2,
			'name' => 'task',

		);
	}

	public function get_lang()
	{
		$default_lang = 'en';
		helper('cookie');
		$lang = get_cookie($this->cookie_lang_index, true);

		return $lang == null ? $default_lang : $lang;
	}

	private function create_session_user($session_id, $user_id = null, $is_authenticated = false, $is_remember = false)
	{
		$model = new SessionUserModel();

		$session_user_id = $model->insert(array(
			'session_id' => $session_id,
			'user_id' => $user_id,
			'is_authenticated' => $is_authenticated,
			'is_remember' => $is_remember,
			'is_active' => true,
		));

		$session_user_date_activity = new SessionUserDateActivityModel();
		$session_user_date_activity->new_fork($session_user_id);
	}

	private function save_new_device($token)
	{
		$session = null;
		$request = \Config\Services::request();
		$agent = $request->getUserAgent();

		if ($agent->isRobot())
		{
			//Todo : Tout les robots doivent avoir la même cookies
			$session = $this->where(array('robot_name' => $agent->getRobot()))->first();
		}

		$session_id = null;

		// Request is not send by robot
		if ($session == null) {	

			//check if device already saved
			$model = $this->where('token', $token)->first();

			if ($model == null) {
				$session_id = $this->insert(array(
					'token' => $token,
					'browser' => $agent->getBrowser(),
					'browser_version' => $agent->getVersion(),
					'mobile_name' => $agent->getMobile(),
					'robot_name' => $agent->getRobot(),
					'referer' => $agent->getReferrer(),
					'user_agent' => $agent->getAgentString(),
					'plateform' => $agent->getPlatform()
				));
			}else{
				$session_id = $model['id'];
			}
		}
		else{
			$this->set_user_token($session->token);
			$session_id = $session['id'];
		}

		return $session_id;
	}

	public function login_device_user($session_id, $user_id, $remember)
	{
		$model = new SessionUserModel();

		$session_user = $model->get_session_user($session_id, $user_id);

		if ($session_user != null) {
			$model->disable_session($session_id);
			$model->enable_session($session_user['id']);
			return true;
		}

		$model->disable_session($session_id);
		$this->create_session_user($session_id, $user_id, true, $remember);

		//Todo : send hub message here
	}

	public function logout_device($session_id)
	{
		$model = new SessionUserModel();
		
		$model->disable_session($session_id);
		$model->enable_anonymous_session($session_id);
	}

	private function session_has_expired($session_user)
	{
		if ($session_user['is_remember']) {
			return false;
		}

		$session_user_date_activity = new SessionUserDateActivityModel();

		return $session_user_date_activity->has_expired($session_user['id']);
	}

	private function set_user_token($token)
	{
		$this->user_token = $token;
	}

	public function get_user_token()
	{
		return $this->user_token == null ? '0' : $this->user_token;
	}

	public function is_superadmin()
	{
		return $this->user_identity['user_role_id'] == 2;
	}

	public function is_authorized($module_name, $method, $value = null)
	{
		return UserRoleModel::get_role_autorization($this->user_identity['user_role_rules'], $module_name, $method, $value);
	}

}