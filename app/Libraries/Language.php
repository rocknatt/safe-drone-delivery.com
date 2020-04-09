<?php 

namespace App\Libraries;

use CodeIgniter\Language\Language as CoreLanguage;

class Language extends CoreLanguage
{
	public function load_default_lang($local)
	{
		//loading local lang
		$lang = $this->load('CLT', $local, true);
		$lang = array_merge($lang, $this->load('STD', $local, true));

		return $lang;
	}
}