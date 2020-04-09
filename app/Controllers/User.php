<?php 
namespace App\Controllers;

use App\Models\User\UserModel;
use App\Models\User\UserRoleModel;
use CodeIgniter\API\ResponseTrait;

class User extends BaseController
{
	use ResponseTrait;

	/**
     * controller default model
     */
    private $model;

    /**
     * Constructor.
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $this->model = new UserModel();
        $this->model->set_session($this->session);
    }

	public function index()
	{

	}

	public function get_user_identity()
	{
		return $this->respond($this->session->user_identity);
	}

	public function role()
	{
		$data_validation = array(

			'user_id' => array(
	            'rules'  => 'required',
	            'errors' => array(
	            	'required' => lang('STD.std_required_err')
	            )
			),
			'user_role_id' => array(
	            'rules'  => 'required',
	            'errors' => array(
	            	'required' => lang('STD.std_required_err'),
	            )
			)

		);

		$error = lang('STD.std_bad_payload');

		if ($this->validate($data_validation))
	    {
	        $user_id = $this->request->getPost('user_id');
	        $user_role_id = $this->request->getPost('user_role_id');

	        $user = $this->model->find($user_id);

	        if ($user == null) {
	        	return $this->failNotFound();
	        }

	        if (!$this->model->can_update_role($user)) {
	        	return $this->failForbidden();
	        }

	        $this->model->where('id', $user_id)
	        		->set('user_role_id', $user_role_id)
	        		->update();

	        $user_role_model = new UserRoleModel();
	        $user_role = $user_role_model->find($user_role_id);

	        return $this->respondCreated(array(
	        	'user_role_id' => $user_role_id,
	        	'user_role' => $user_role['designation']
	        ));
	    }

	    //Bad request
	    return $this->fail($error);
	}

	public function block()
	{
		$data_validation = array(

			'user_id' => array(
	            'rules'  => 'required',
	            'errors' => array(
	            	'required' => lang('STD.std_required_err')
	            )
			),

		);

		$error = lang('STD.std_bad_payload');

		if ($this->validate($data_validation))
	    {
	        $user_id = $this->request->getPost('user_id');
	        $user = $this->model->find($user_id);

	        if ($user == null) {
	        	return $this->failNotFound();
	        }

	        if (!$this->model->can_toggle_block($user)) {
	        	return $this->failForbidden();
	        }

	        $this->model->where('id', $user_id)
	        		->set('is_blocked', true)
	        		->update();

	        return $this->respondCreated();
	    }

	    //Bad request
	    return $this->fail($error);
	}

	public function unblock()
	{
		$data_validation = array(

			'user_id' => array(
	            'rules'  => 'required',
	            'errors' => array(
	            	'required' => lang('STD.std_required_err')
	            )
			),

		);

		$error = lang('STD.std_bad_payload');

		if ($this->validate($data_validation))
	    {
	        $user_id = $this->request->getPost('user_id');
	        $user = $this->model->find($user_id);

	        if ($user == null) {
	        	return $this->failNotFound();
	        }

	        if (!$this->model->can_toggle_block($user)) {
	        	return $this->failForbidden();
	        }

	        $this->model->where('id', $user_id)
	        		->set('is_blocked', false)
	        		->update();

	        return $this->respondCreated();
	    }

	    //Bad request
	    return $this->fail($error);
	}

	public function forgot_pass($method)
	{
		switch ($methode) {
			case 'phone_number':
				if ($this->validate(
					array(
						'phone' => array(
				            'rules'  => 'required',
				            'errors' => array(
				            	'required' => lang('STD.std_required_err')
				            )
						)
					)
				)) {
					$phone = $this->request->getPost('phone');

					//Todo : reset usign phone number
					// $model = $this->user->find_user($phone);

					// if ($model == null) {
					// 	$response->err_msg = $this->lang->line('clt_this_phone_number_was_not_used_on_this_plateforme');
					// }else{
					// 	$unique_id = $this->user->get_code_confirmation($model);
					// 	// $this->user->send_code_by_phone_number($model, $unique_id, $phone);
					// 	$response->sent = true;
					// }
				}
				break;

			case 'email':
				if ($this->validate(
					array(
						'email' => array(
				            'rules'  => 'required|valid_email',
				            'errors' => array(
				            	'required' => lang('STD.std_required_err'),
	            				'valid_email' => lang('STD.std_email_valid_err'),
				            )
						)
					)
				)) {
					$email = $this->request->getPost('email');

					$user = $this->model->find_user($email);

					if ($user == null) {
			        	return $this->failNotFound();
			        }

					//Todo : reset usign phone number
					//$unique_id = $this->user->get_reset_uniqid($model);
				}
				break;

			case 'code_recovery':
				if ($this->validate(
					array(
						'code' => array(
				            'rules'  => 'required',
				            'errors' => array(
				            	'required' => lang('STD.std_required_err')
				            )
						)
					)
				)) {
					$code = $this->request->getPost('code');

					$user = $this->model->where('uniq_id_reset_pass', $code);

					// Todo : check if link expire
					if ($user == null) {
			        	return $this->failNotFound();
			        }

			  //       if ($model === null) {
					// 	$response->err_msg = $this->lang->line('clt_code_you_sent_is_wrong');
					// }
					// else{

					// 	$time_span = get_date_time_span_from_now($model->date_ajout_uniq_id_reset_pass);

					// 	if ($time_span->d > 0 || $time_span->h > 0 || $time_span->i > 30) {
					// 		$response->err_msg = $this->lang->line('clt_code_confirmation_expired');
					// 	}
					// 	else{
					// 		$response->id = $model->id;
					// 		$response->uniq_id_reset_pass = $model->uniq_id_reset_pass;
					// 		$response->granted = true;
					// 	}
					// }
				}
				break;
			
			default:
				
				break;
		}

	    //Bad request
	    return $this->fail($error);
	}

	public function update_password()
	{
		$data_validation = array(

			'user_id' => array(
	            'rules'  => 'required',
	            'errors' => array(
	            	'required' => lang('STD.std_required_err')
	            )
			),
			'old_password' => array(
	            'rules'  => 'required',
	            'errors' => array(
	            	'required' => lang('STD.std_required_err'),
	            )
			),
			'new_password' => array(
	            'rules'  => 'required',
	            'errors' => array(
	            	'required' => lang('STD.std_required_err'),
	            )
			),
			'confirm_new_password' => array(
	            'rules'  => 'required',
	            'errors' => array(
	            	'required' => lang('STD.std_required_err'),
	            )
			),

		);

		$error = lang('STD.std_bad_payload');

		if ($this->validate($data_validation))
	    {
	        $user_id = $this->request->getPost('user_id');
	        $old_password = $this->request->getPost('old_password');
	        $new_password = $this->request->getPost('new_password');
	        $confirm_new_password = $this->request->getPost('confirm_new_password');

	        $user = $this->model->find($user_id);

	        if ($user == null) {
	        	return $this->failNotFound();
	        }

	        if (!$this->model->can_change_password($user)) {
	        	return $this->failForbidden();
	        }

	        if ($user['password'] != hash('md5', $old_password)) {
	        	return $this->fail(lang('STD.std_password_incorrect'));
	        }

	        if ($new_password != $confirm_new_password) {
	        	return $this->fail(lang('STD.std_password_not_match'));
	        }

	        if ($user['password'] == $new_password) {
	        	return $this->fail(lang('STD.std_password_same'));
	        }

	        //Todo : log last time password updated
	        $this->model->where('id', $user_id)
	        		->set(array(
	        			'password' => hash('md5', $new_password),

	        		))
	        		->update();

	        return $this->respondCreated();
	    }

	    //Bad request
	    return $this->fail($error);
	}

	public function reset_password($reset_token = '')
	{
		$data_validation = array(

			'user_id' => array(
	            'rules'  => 'required',
	            'errors' => array(
	            	'required' => lang('STD.std_required_err')
	            )
			),
			'new_password' => array(
	            'rules'  => 'required',
	            'errors' => array(
	            	'required' => lang('STD.std_required_err'),
	            )
			)

		);

		if ($this->validate($data_validation))
	    {
	        $user_id = $this->request->getPost('user_id');
	        $new_password = $this->request->getPost('new_password');
	        $uniq_id = $this->request->getPost('uniq_id');

	        $user = $this->model->find($user_id);

	        if ($user == null) {
	        	return $this->failNotFound();
	        }

	        // Todo : check if link is expired
	        if ($uniq_id != $user['uniq_id_reset_pass'] ||
	        	!$this->model->can_reset_password($user)) {
	        	return $this->failForbidden();
	        }

	        $this->model->where('id', $user_id)
	        		->set('password', hash('md5', $new_password))
	        		->update();

	        return $this->respondCreated();
	    }

	    //Bad request
	    return $this->respond(array('reset_token' => $token));
	}

	public function login()
	{
		$data_validation = array(

			'user_name' => array(
	            'rules'  => 'required',
	            'errors' => array(
	            	'required' => lang('std_required_err')
	            )
			),
			'password' => array(
	            'rules'  => 'required',
	            'errors' => array(
	            	'required' => lang('std_required_err')
	            )
			)

		);

		$error = lang('STD.std_bad_payload');

		if ($this->validate($data_validation))
	    {
	        $user_name = $this->request->getPost('user_name');
			$password = $this->request->getPost('password');
			$remember = isset($_POST['remember']);

			//validate user

			$auth_result = $this->model->auth($user_name, $password);

			if ($auth_result['status'] === 'auth_ok') {
				$this->session->login_device_user($this->session->user_identity['session_id'], $auth_result['user']['id'], $remember);

				return $this->respondCreated();
			}

			if ($auth_result['status'] === 'auth_fail') {
				$error = lang('STD.std_password_does_match_err');
			}

			if ($auth_result['status'] === 'auth_blocked') {
				$error = lang('STD.std_user_blocked');
			}
	    }

	    //Bad request
	    return $this->fail($error);
	}

	public function logout()
	{
		$this->session->logout_device($this->session->user_identity['session_id']);

		return $this->respondCreated();
	}

	//--------------------------------------------------------------------

}
