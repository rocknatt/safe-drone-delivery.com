<?php 

namespace App\Controllers;

use Config\Services;
use CodeIgniter\API\ResponseTrait;
use App\Models\Mzara\Product\ProductModel;

class Home extends BaseController
{
	use ResponseTrait;

	// public function index()
	// {
	// 	return view('welcome_message');
	// }

	public function index()
	{
		return view('home/index');
	}

	public function shop()
	{
		$product_model = new ProductModel();
		$payload = $this->get_list_payload();
        $payload['product_category_id'] = $this->request->getGet('product_category_id');
        $payload['all'] = $this->request->getGet('all');

        //Todo : Logo product_brand here
        $data_list = $product_model->get_list($payload);
        $total = $product_model->get_count($payload);

        $pager = \Config\Services::pager();
        $pager->setPath('dev/safe-drone-delivery.com/public/home/shop', 'shop');

		return view('home/shop', array(
            'total' => $total,
            'count' => count($data_list),
            'page' => $payload['page'],
            'limit' => $payload['limit'],
            'product_list' => $data_list,
            'pager' => $pager,
        ));
	}

	public function payement()
	{
		return view('home/payement');
	}

	public function checkout()
	{
		return view('home/checkout');
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
