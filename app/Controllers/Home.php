<?php 

namespace App\Controllers;

use Config\Services;
use CodeIgniter\API\ResponseTrait;

class Home extends BaseController
{
	use ResponseTrait;

	public function index()
	{
		return view('welcome_message');
	}

	public function get_lang()
	{
		$local = $this->session->get_lang();

		$payload = Services::language($local)->load_default_lang($local);

		return $this->respond(array(
			'local' => $local,
			'data' => $payload
		));
	}

	public function set_lang($value='')
	{
		setcookie($this->cookie_token_index, $token, strtotime('last day of december this year'), '/');

		return $this->respondCreated();
	}

	public function get_current_user()
	{
		return $this->respond($this->session->user_identity);
	}

	public function options()
	{
		return $this->respond(array());
	}

	//--------------------------------------------------------------------

}
