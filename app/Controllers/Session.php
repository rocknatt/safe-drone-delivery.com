<?php 

namespace App\Controllers;

use App\Models\SessionModel;
use App\Models\SessionUserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Config\Services;

class Session extends BaseController
{
    use ResponseTrait;

    /**
     * controller default model
     * @var App\Models\ProspectModel
     */
    private $model;

    /**
     * Constructor.
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
    }

    public function index()
    {
        
    }

    public function device_list()
    {
        $key = $this->request->getGet('key');
        $order_by = $this->request->getGet('order_by');
        $sort = $this->request->getGet('sort');
        $nb = $this->request->getGet('nb');
        $debut = $this->request->getGet('debut');

        $data_list = $this->session->get_device_list($key, $order_by, $sort, $nb, $debut);

        return $this->respond($data_list);
    }

    public function test()
    {
        $session_user_model = new SessionUserModel();
        $session_user = $session_user_model->get_active('5b5cc0d13eb5e7');

        var_dump($session_user);
    }

}