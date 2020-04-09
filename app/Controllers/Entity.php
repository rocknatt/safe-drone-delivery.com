<?php 

namespace App\Controllers;

use App\Models\EntityModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Config\Services;

class Entity extends BaseController
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

        $this->model = new EntityModel();
        $this->model->set_session($this->session);
    }

    public function index()
    {
        $key = $this->request->getGet('key');
        $order_by = $this->request->getGet('order_by');
        $sort = $this->request->getGet('sort');
        $nb = $this->request->getGet('nb');
        $debut = $this->request->getGet('debut');
        $entity = $this->request->getGet('entity');
        if (!$this->model->set_entity($entity)) {
            return $this->failForbidden();
        }

        $header_list = $this->model->get_header_list();
        $data_list = $this->model->get_list($key, $order_by, $sort, $nb, $debut);
        $count = $this->model->get_count($key);

        return $this->respond(array(
            'count' => $count,
            'data_list' => $data_list,
            'header_list' => $header_list,
        ));
    }

    public function create()
    {
        $payload = $this->request->getJSON(true);
        $validator = Services::validation();

        if (!$this->model->set_entity($payload['entity'])) {
            return $this->failForbidden();
        }

        if (!$this->model->can_create()) {
            return $this->failForbidden();
        }

        $data_validation = $this->model->get_validation_rules();

        $result = $validator->setRules($data_validation)
                            ->run($payload);

        if ($result)
        {
            $payload = $this->model->get_parsed_payload($payload);

            //validate user
            $id = $this->model->insert($payload);
            $select_list_canvas = $this->model->get_select_list_canvas();

            $payload = $this->model->find($id);
            $payload['label'] = $payload[$select_list_canvas['label']];
            $payload['value'] = $payload[$select_list_canvas['value']];

            return $this->respondCreated($payload);
        }

        $error = $validator->getErrors();

        //Bad request
        return $this->fail($error);
    }

    public function read($id)
    {
        $entity = $this->request->getGet('entity');
        if (!$this->model->set_entity($entity)) {
            return $this->failForbidden();
        }

        $model = $this->model->find($id);

        if ($model == null) {
            return $this->failNotFound();
        }

        if (!$this->model->can_read()) {
            return $this->failForbidden();
        }

        $model = $this->model->write_accessibility($model);

        //Todo : role rule is gived by functionnality, loaded by client front end
        return $this->respond($model);
    }

    public function update()
    {
        $payload = $this->request->getJSON(true);
        $validator = Services::validation();

        if (!$this->model->set_entity($payload['entity'])) {
            return $this->failForbidden();
        }

        $data_validation = $this->model->get_validation_rules();
        $data_validation['id'] = array(
                'label' => 'id',
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_field_required_err')
                )
            );

        $result = $validator->setRules($data_validation)
                            ->run($payload);

        if ($result)
        {
            $id = $payload['id'];
            $model = $this->model->find($id);

            if ($model == null) {
                return $this->failNotFound();
            }

            if (!$this->model->is_editable($model)) {
                return $this->failForbidden();
            }

            $payload = $this->model->get_parsed_payload($payload);

            $this->model->where('id', $id)
                    ->set($payload)
                    ->update();

            return $this->respondCreated();
        }

        $error = $validator->getErrors();

        // //Bad request
        return $this->fail($error);
    }

    public function delete()
    {
        $payload = $this->request->getJSON(true);
        $validator = Services::validation();

        if (!$this->model->set_entity($payload['entity'])) {
            return $this->failForbidden();
        }

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
            $model = $this->model->find($id);

            if ($model == null) {
                return $this->failNotFound();
            }

            if (!$this->model->is_deletable($model)) {
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
        $entity = $this->request->getGet('entity');
        if (!$this->model->set_entity($entity)) {
            return $this->failForbidden();
        }

        $data = $this->model->get_order_list();

        return $this->respond($data);
    }

    public function get_access()
    {
        $entity = $this->request->getGet('entity');
        if (!$this->model->set_entity($entity)) {
            return $this->failForbidden();
        }

        $data = $this->model->get_access();

        return $this->respond($data);
    }

    public function get_form()
    {
        $entity = $this->request->getGet('entity');
        if (!$this->model->set_entity($entity)) {
            return $this->failForbidden();
        }

        $data = $this->model->get_form();
        $data['title'] = lang('STD.std_' . $this->model->get_entity_name());
        array_push($data['data_list'], array(
            'type' => 'hidden',
            'name' => 'entity',
            'value' => $this->model->get_entity_name()
        ));

        return $this->respond($data);
    }

    public function get_select_list($entity)
    {
        if (!$this->model->set_entity($entity)) {
            return $this->failForbidden();
        }

        $select_list_canvas = $this->model->get_select_list_canvas();

        $data = $this->model->get_select_list($select_list_canvas['label'], $select_list_canvas['value']);

        return $this->respond($data);
    }

}