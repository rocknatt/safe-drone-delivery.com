<?php

namespace App\Models;

use WebSocket\Client;
use WebSocket\ConnectionException;
use App\Libraries\RatchetClient;
use App\Models\SessionUserModel;
use CodeIgniter\Model;
use CodeIgniter\CLI\CLI;

class HubModel extends Model
{
	public $task_token;
	private $client;

	public $ratchet_client_config = [
		'server' => '127.0.0.1',
		'host' => '0.0.0.0',
		'port' => 4000,
	    'auth' => true,
	    'debug' => true
	];
	
	function __construct()
	{
		parent::__construct();

		$this->task_token = '5ca30d2fb468f';
		if (ENVIRONMENT !== 'testing')
		{
			$this->client = new Client('ws://'. $this->ratchet_client_config['server'] . ':'.  $this->ratchet_client_config['port']);
		}
	}

	public $session;

    public function set_session($session)
    {
    	$this->session = $session;
    }

	public function init_websocket()
	{
		if ($this->client->isConnected()) {
			return true;
		}
		
		$_COOKIE['id'] = $this->task_token;
		helper('ratchet_client');
		$ratchet_client = new RatchetClient(array('ratchet_client' => $this->ratchet_client_config));

		error_reporting(-1);
		ini_set('display_errors', 1);

		//Run server
		$ratchet_client->set_callback('auth', array($this, '_auth'));
		$ratchet_client->set_callback('event', array($this, '_event'));
		$ratchet_client->set_callback('close', array($this, '_close'));
		$ratchet_client->run();
	}

	public function _close($connection = null, $client_list = null, $server = null)
	{
		$connection_nb = 0;
		foreach ($client_list as $client) {
			// There is connection on the same device
			if (isset($client->subscriber_id) && $client->subscriber_id['id'] != 0 && $client->subscriber_id['token'] == $connection->subscriber_id['token']) {
				$connection_nb = $connection_nb + 1;
			}
		}

		if ($connection_nb == 1) {

			$session_user_model = new SessionUserModel();
			$session_user = $session_user_model->get_active($connection->subscriber_id['token']);

			if ($session_user != null) {
				$session_user_date_activity = new SessionUserDateActivityModel();
				$session_user_date_activity->close_fork($session_user['id']);

				//ulo: user_logout
				$data = array(
					'user_id' => 0, 
					'recipient_id' => 0,
					'token' => $this->task_token, //token de task (utilisé en interne)
					'type' => 'user_logged_out',
					'message' => array('user_id' => $session_user['user_id'])
				);
				$server->broadcast($data, json_encode($data), $connection);
			}
			
		}

		return true;
	}

	public function _auth($data = null)
	{
		//Todo : check if data sent from user right

		return array('token' => $data->token, 'id' => $data->user_id);
	}

	public function _event($data = null, $connection = null, $client_list = null, $server = null)
	{
		if ($data->message == 'auth') {
			
			$connection_nb = 0;
			foreach ($client_list as $client) {
				//request on the same device
				if (isset($client->subscriber_id)  && $client->subscriber_id['id'] != 0 && $client->subscriber_id['token'] == $data->token) {
					$connection_nb = $connection_nb + 1;
				}
			}
			

			if ($connection_nb == 1) {

				$session_user_model = new SessionUserModel();
				$session_user = $session_user_model->get_active($data->token);
				
				if ($session_user != null) {
					// $session_user_date_activity = new SessionUserDateActivityModel();
					// $session_user_date_activity->new_fork($session_user['id']);

					//ul: user_logged
					$data = array(
						'user_id' => 0, 
						'recipient_id' => 0,
						'token' => $this->task_token, //token de task (utilisé en interne)
						'type' => 'user_logged_in',
						'message' => array('user_id' => $session_user['user_id'])
					);
					$server->broadcast($data, json_encode($data), $connection);
				}

			}
		}
		//Log user date activity

		// echo 'Hey ! Im a Event Callback \n';
	}

	public function push_message($type, $message, $user_id = null)
	{

		if (ENVIRONMENT === 'testing')
		{
			return true;
		}

		try {

			$token = $this->task_token;
			if ($this->session != null) {
				$token = $this->session->user_identity['token'];
			}
			
			$this->client->send(json_encode(
				array(
					'user_id' => 0, 
					'recipient_id' => $user_id,// si user_id est null, ça va broadcaster
					'token' => $token, // pour eviter le redondance
					'type' => $type,
					'message' => $message
				)
			));

			return true;

		} catch (ConnectionException $e) {
			return false;
		} catch (Exception $e){
			return false;
		}
		
	}

}

/* End of file hub_model.php */
/* Location: ./application/models/hub_model.php */