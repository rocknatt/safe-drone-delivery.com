<?php 

namespace App\Libraries;

use CodeIgniter\Email\Email as CoreEmail;

class Email extends CoreEmail
{
	public function get_debugger_messages()
	{
		return $this->debugMessage;
	}
}