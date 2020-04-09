<?php 

namespace App\Controllers;

use App\Models\User\UserRoleModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Config\Services;

class UserRole extends BaseController
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

        $this->model = new UserRoleModel();
        $this->model->set_session($this->session);
    }

    public function index()
    {
        $key = $this->request->getGet('key');
        $order_by = $this->request->getGet('order_by');
        $sort = $this->request->getGet('sort');
        $nb = $this->request->getGet('nb');
        $debut = $this->request->getGet('debut');

        $role_list = $this->model->get_list($key, $order_by, $sort, $nb, $debut);

        return $this->respond($role_list);
    }

    public function create()
    {
        if (!$this->session->is_superadmin()) {
            return $this->failForbidden();
        }

        $payload = $this->request->getJSON(true);
        $validator = Services::validation();

        $data_validation = array(

            'designation' => array(
                'label' => lang('STD.std_designation'),
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_field_required_err')
                )
            ),

        );

        $result = $validator->setRules($data_validation)
                            ->run($payload);

        if ($result)
        {
            $designation = $payload['designation'];
            $comments = $payload['comments'];

            //validate user
            $user_role_id = $this->model->insert(array('designation' => $designation, 'comments' => $comments));

            return $this->respondCreated(array(
                'id' => $user_role_id,
            ));
        }

        $error = $validator->getErrors();

        //Bad request
        return $this->fail($error);
    }

    public function read($id)
    {
        if (!$this->session->is_superadmin()) {
            return $this->failForbidden();
        }

        $user_role = $this->model->find($id);

        if ($user_role == null) {
            return $this->failNotFound();
        }

        $user_role['rules'] = json_decode($user_role['rules']);
        $user_role['is_editable'] = $this->model->is_editable();
        $user_role['is_deletable'] = $this->model->is_deletable();
        $user_role['argument_list'] = $this->model->get_argument();

        //Todo : role rule is gived by functionnality, loaded by client front end
        return $this->respond($user_role);
    }

    public function update()
    {
        if (!$this->session->is_superadmin()) {
            return $this->failForbidden();
        }

        $payload = $this->request->getJSON(true);
        $validator = Services::validation();

        $data_validation = array(

            'id' => array(
                'label' => 'id',
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_field_required_err')
                )
            ),
            'designation' => array(
                'label' => lang('STD.std_designation'),
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_field_required_err')
                )
            ),

        );

        $result = $validator->setRules($data_validation)
                            ->run($payload);

        if ($result)
        {
            $id = $payload['id'];
            $user_role = $this->model->find($id);

            if ($user_role == null) {
                return $this->failNotFound();
            }

            if (!$this->model->is_editable($user_role)) {
                return $this->failForbidden();
            }

            $designation = $payload['designation'];
            $rules = $payload['rules'];
            $comments = $payload['comments'];

            $this->model->where('id', $id)
                    ->set(array('designation' => $designation, 'rules' => json_encode($rules), 'comments' => $comments))
                    ->update();

            return $this->respondCreated();
        }

        $error = $validator->getErrors();

        // //Bad request
        return $this->fail($error);
    }

    public function delete()
    {
        if (!$this->session->is_superadmin()) {
            return $this->failForbidden();
        }

        $payload = $this->request->getJSON(true);
        $validator = Services::validation();

        $data_validation = array(

            'id' => array(
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_required_err')
                )
            ),

        );

        $result = $validator->setRules($data_validation)
                            ->run($payload);

        if ($result)
        {
            $id = $payload['id'];
            $user_role = $this->model->find($id);

            if ($user_role == null) {
                return $this->failNotFound();
            }

            if (!$this->model->is_deletable($user_role)) {
                return $this->failForbidden();
            }

            $this->model->delete($id);

            return $this->respondNoContent();
        }

        $error = $validator->getErrors();

        //Bad request
        return $this->fail($error);
    }

    public function get_order_list()
    {
        $data = array(
            array('label' => lang('STD.std_name'), 'value' => 'designation'),
            array('label' => lang('STD.std_date'), 'value' => 'created_at'),
        );

        return $this->respond($data);
    }

    public function get_access()
    {
        $data = array(
            'can_create' => $this->model->can_create()
        );

        return $this->respond($data);
    }

}