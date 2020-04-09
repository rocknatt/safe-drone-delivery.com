<?php 

namespace App\Controllers;

use Config\Services;
use CodeIgniter\API\ResponseTrait;

class Hub extends BaseController
{
	use ResponseTrait;

	public function index()
	{
		// return view('welcome_message');
	}

	public function websocket()
	{
		//Exception : this index is only defined with cli but this method is used to boot websocket form http
		$_SERVER['argc'] = 2;
		$_SERVER['argv'] = array('task.php', 'hub', 'init', '-');
		$this->hub->init_websocket();
	}

	public function init()
	{
		$this->hub->init_websocket();
	}

	//--------------------------------------------------------------------

}
