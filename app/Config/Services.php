<?php 

namespace Config;

use CodeIgniter\Config\Services as CoreServices;
use CodeIgniter\Config\BaseConfig;

use App\Libraries\Language;
use App\Libraries\Email;

require_once SYSTEMPATH . 'Config/Services.php';

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends CoreServices
{

	//    public static function example($getShared = true)
	//    {
	//        if ($getShared)
	//        {
	//            return static::getSharedInstance('example');
	//        }
	//
	//        return new \CodeIgniter\Example();
	//    }


	//--------------------------------------------------------------------

	/**
	 * Responsible for loading the language string translations.
	 *
	 * @param string  $locale
	 * @param boolean $getShared
	 *
	 * @return \CodeIgniter\Language\Language
	 */
	public static function language(string $locale = null, bool $getShared = true)
	{
		if ($getShared)
		{
			return static::getSharedInstance('language', $locale)
							->setLocale($locale);
		}

		$locale = ! empty($locale) ? $locale : static::request()
						->getLocale();

		return new Language($locale);
	}

	/**
	 * The Email class allows you to send email via mail, sendmail, SMTP.
	 *
	 * @param null    $config
	 * @param boolean $getShared
	 *
	 * @return \CodeIgniter\Email\Email|mixed
	 */
	public static function email($config = null, bool $getShared = true)
	{
		if ($getShared)
		{
			return static::getSharedInstance('email', $config);
		}
		if (empty($config))
		{
			$config = new \Config\Email();
		}

		$email = new Email();
		$email->setLogger(static::logger(true));
		return $email;
	}
}
