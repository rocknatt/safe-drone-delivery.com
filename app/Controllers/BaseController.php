<?php
namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use App\Models\Session\SessionModel;
use App\Models\HubModel;
use CodeIgniter\Controller;


class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];

	/**
	 * Current session of user sending request 
	 * @var App\Models\SessionModel
	 */
	protected $session = null;

	/**
	 * This can be set if switch to tools or client or event a different plateform
	 * @var string
	 */
	protected $plateform_name = '';

	/**
	 * Current entity name, that will be returned when get_title function is called
	 * @var string
	 */
	protected $current_entity = '';

	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{

		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();

		//Helper
		helper('array');
		
		//session
		if (!is_cli()) {
			$this->session = new SessionModel();
		}
		
		//folder_path
		$folder_path = dirname(FCPATH);
		$folder_path = dirname($folder_path);// outside mzara-folder

		define('FOLDER_WRITABLE_PATH', $folder_path . '/safe-dron-delivery-files');
		define('IMAGE_FOLDER_WRITABLE_PATH', FOLDER_WRITABLE_PATH . '/img');
		define('CLOUD_FOLDER_WRITABLE_PATH', FOLDER_WRITABLE_PATH . '/cloud');
		define('MAIL_FOLDER_WRITABLE_PATH', FOLDER_WRITABLE_PATH . '/mail');
		define('CHAT_FOLDER_WRITABLE_PATH', FOLDER_WRITABLE_PATH . '/chat');

		if (!is_dir(FOLDER_WRITABLE_PATH)) {
			mkdir(FOLDER_WRITABLE_PATH, 777);
		}

		if (!is_dir(IMAGE_FOLDER_WRITABLE_PATH)) {
			mkdir(IMAGE_FOLDER_WRITABLE_PATH, 777);
		}

		if (!is_dir(CLOUD_FOLDER_WRITABLE_PATH)) {
			mkdir(CLOUD_FOLDER_WRITABLE_PATH, 777);
		}

		if (!is_dir(MAIL_FOLDER_WRITABLE_PATH)) {
			mkdir(MAIL_FOLDER_WRITABLE_PATH, 777);
		}

		if (!is_dir(CHAT_FOLDER_WRITABLE_PATH)) {
			mkdir(CHAT_FOLDER_WRITABLE_PATH, 777);
		}
		
		//cross-origin
		if (ENVIRONMENT !== 'production')
		{
			$this->response->setHeader('Access-Control-Allow-Origin', '*');
			$this->response->setHeader('Access-Control-Allow-Headers', 'content-type, x-requested-with');
			$this->response->setHeader('Access-Control-Allow-Methods', 'PUT, DELETE, OPTIONS');


			if ($request->getMethod() === 'options') {
				$this->response->send();
				die();
				exit();
			}
		}

		//load ajax bundle
		if (ENVIRONMENT === 'production' && !is_cli() && !$this->request->isAJAX())
		{
			$this->response->setStatusCode(200)
               ->setBody(view('ajax'))
               ->send();
			die();
			exit();
		}

		$this->plateform_name = 'Safe drone delivery';
		
		//load hub
		$this->hub = new HubModel();
		$this->hub->set_session($this->session);

		//Load log
		//Todo : check if class exist
		$this->log = new \App\Models\Mzara\LogModel();
		$this->log->set_session($this->session);

		//Load user_notification
		//Todo : check if class exist
		$this->user_notification = new \App\Models\User\UserNotificationModel();
		$this->user_notification->set_session($this->session);
	}

	public function return_file($path, $disposition = 'inline')
	{
		$file = new \CodeIgniter\Files\File($path);

		$mime = $file->getMimeType();
		$filesize = $file->getSize();
		$extension = $file->guessExtension();
		$filename = $file->getBasename();


		if (ENVIRONMENT !== 'testing')
		{
			// Clean output buffer
			if (ob_get_level() !== 0 && @ob_end_clean() === FALSE)
			{
				@ob_clean();
			}

			// Generate the server headers
			header('Content-Type: '.$mime);
			header('Content-Disposition: '. $disposition .'; filename="'.$filename.'"');
			header('Expires: 0');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '.$filesize);
			header('Cache-Control: private, no-transform, no-store, must-revalidate');

			// $this->response->setHeader('Content-Type', $mime)
			// 				->setHeader('content-Length', $filesize)
			// 				->setHeader('Content-Disposition', $disposition . '; filename="'.$filename.'"')
			// 				->setHeader('Cache-Control', 'private')
			// 				->appendHeader('Cache-Control', 'no-transform')
			// 				->appendHeader('Cache-Control', 'no-store')
			// 				->appendHeader('Cache-Control', 'no-revalidate')
			// 				->setHeader('Expires', 0);

			readfile($path);
			die();
			exit();
		}

		return true;
	}

	public function redirect($uri)
	{
		if (ENVIRONMENT !== 'testing')
		{
			if (ENVIRONMENT !== 'production')
			{
				header('Access-Control-Allow-Origin: *');
				header('Access-Control-Allow-Headers: content-type, x-requested-with');
				header('Access-Control-Allow-Methods: PUT, DELETE, OPTIONS');
			}
			header('Location: ' . site_url($uri));
			die();
			exit();
		}

		return true;
	}

	public function get_title($title='')
	{
		$str = '';

		if ($title != null) {
			$str .= $title;
		}

		if ($this->current_entity != null) {
			$str .= $this->write_sep($str);
			$str .= $this->current_entity;
		}

		if ($this->plateform_name != null) {
			$str .= $this->write_sep($str);
			$str .= $this->plateform_name;
		}

		return $str;
	}

	public function get_list_payload()
	{
		$key = $this->request->getGet('key');
        $order_by = $this->request->getGet('order_by');
        $sort = $this->request->getGet('sort');
        $limit = $this->request->getGet('limit');
        $page = $this->request->getGet('page');

        if ($limit == null) {
        	$limit = 10;
        }

        if ($page == null || $page < 0) {
        	$page = 0;
        }

        if ($key == null) {
        	$key = '';
        }

        if ($sort == null) {
        	$sort = 'desc';
        }

        // if ($order_by == null) {
        // 	$order_by = 'created_at';
        // }

        return array(
        	'key' => $key,
        	'order_by' => $order_by,
        	'sort' => $sort,
        	'page' => $page,
        	'limit' => $limit,
        	'offset' => $page * $limit
        );
	}

	private function write_sep($str)
	{
		return strlen($str) > 0 ? ' - ' : '';
	}

}
