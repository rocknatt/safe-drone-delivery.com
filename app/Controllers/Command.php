<?php 

namespace App\Controllers;

use App\Models\Command\CommandModel;
use App\Models\Command\CommandLineModel;
use App\Models\StateModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Config\Services;
use CodeIgniter\I18n\Time;

class Command extends BaseController
{
    use ResponseTrait;

    /**
     * controller default model
     * @var App\Models\CommandModel
     */
    private $model;

    private $line_model;

    /**
     * Constructor.
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $this->model = new CommandModel();
        $this->model->set_session($this->session);

        $this->line_model = new CommandLineModel();
    }

    public function index()
    {
        $key = $this->request->getGet('key');
        $order_by = $this->request->getGet('order_by');
        $sort = $this->request->getGet('sort');
        $nb = $this->request->getGet('nb');
        $debut = $this->request->getGet('debut');

        if (!$this->model->can_access_list()) {
            return $this->failForbidden();
        }

        $data_list = $this->model->get_list($key, $order_by, $sort, $nb, $debut);
        $count = $this->model->get_count($key);

        return $this->respond(array(
            'count' => $count,
            'data_list' => $this->model->get_view_list($data_list)
        ), 200);
    }

    public function create()
    {
        if (!$this->model->can_create()) {
            return $this->failForbidden();
        }

        $payload = $this->request->getJSON(true);
        $validator = Services::validation();

        $data_validation = array(

            'user_client_id' => array(
                'label' => lang('STD.std_client'),
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_field_required_err')
                )
            ),
            'object' => array(
                'label' => lang('STD.std_object'),
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_field_required_err')
                )
            ),
            'payement_condition_id' => array(
                'label' => lang('STD.std_payement_condition'),
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_field_required_err')
                )
            ),
            'payement_mode_id' => array(
                'label' => lang('STD.payement_mode'),
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
            $user_client_id = $payload['user_client_id'];
            $object = $payload['object'];
            $interlocuter_nom = dot_array_search('interlocuter_nom', $payload);
            $interlocuter_mail = dot_array_search('interlocuter_mail', $payload);
            $interlocuter_telephone = dot_array_search('interlocuter_telephone', $payload);
            $reduction = dot_array_search('reduction', $payload);
            $payement_condition_id = $payload['payement_condition_id'];
            $payement_mode_id = $payload['payement_mode_id'];

            $id = $this->model->insert(array(
                'num' => $this->model->get_num_template(),
                'user_id' => $this->session->user_identity['id'],
                'user_client_id' => $user_client_id,
                'interlocuter_nom' => $interlocuter_nom,
                'interlocuter_mail' => $interlocuter_mail,
                'interlocuter_telephone' => $interlocuter_telephone,
                'object' => $object,
                'reduction' => $reduction,
                'payement_condition_id' => $payement_condition_id,
                'payement_mode_id' => $payement_mode_id,
                'state_id' => StateModel::$state_created,
                'offer_validity' => 15
            ));

            $payload = $this->model->find($id);

            return $this->respondCreated($payload);
            
        }

        $error = $validator->getErrors();

        //Bad request
        return $this->fail($error);
    }

    public function update()
    {
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
            'user_client_id' => array(
                'label' => lang('STD.std_client'),
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_field_required_err')
                )
            ),
            'object' => array(
                'label' => lang('STD.std_object'),
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_field_required_err')
                )
            ),
            'payement_condition_id' => array(
                'label' => lang('STD.std_payement_condition'),
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_field_required_err')
                )
            ),
            'payement_mode_id' => array(
                'label' => lang('STD.payement_mode'),
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
            $model = $this->model->find($id);

            if ($model == null) {
                return $this->failNotFound();
            }

            if (!$this->model->is_editable($model)) {
                return $this->failForbidden();
            }

            $user_client_id = $payload['user_client_id'];
            $object = $payload['object'];
            $interlocuter_nom = dot_array_search('interlocuter_nom', $payload);
            $interlocuter_mail = dot_array_search('interlocuter_mail', $payload);
            $interlocuter_telephone = dot_array_search('interlocuter_telephone', $payload);
            $reduction = dot_array_search('reduction', $payload);
            $payement_condition_id = $payload['payement_condition_id'];
            $payement_mode_id = $payload['payement_mode_id'];

            $this->model->where('id', $id)
                    ->set(array(
                        'num' => $this->model->get_num_template(),
                        'user_client_id' => $user_client_id,
                        'interlocuter_nom' => $interlocuter_nom,
                        'interlocuter_mail' => $interlocuter_mail,
                        'interlocuter_telephone' => $interlocuter_telephone,
                        'object' => $object,
                        'reduction' => $reduction,
                        'payement_condition_id' => $payement_condition_id,
                        'payement_mode_id' => $payement_mode_id,
                    ))
                    ->update();

            return $this->respondCreated();
        }

        $error = $validator->getErrors();

        //Bad request
        return $this->fail($error);
    }

    public function read($id)
    {
        $payload = $this->model->get_data($id);

        if ($payload == null) {
            return $this->failNotFound();
        }

        if (!$this->model->can_read($payload)) {
            return $this->failForbidden();
        }

        // Todo : data confidentiality check here
        return $this->respond($payload);
    }

    public function delete()
    {
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

    public function set_state_validate()
    {
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

            if (!$this->model->can_validate($model)) {
                return $this->failForbidden();
            }

            $next_num = $this->model->get_next_num($model);

            $this->model->where('id', $id)
                        ->set('state_id', StateModel::$state_validated)
                        ->set('date_state_changed', Time::now()->toDateTimeString())
                        ->set('num', $next_num)
                        ->update();

            $payload = $this->model->get_data($id);

            return $this->respondCreated($payload);
        }

        $error = $validator->getErrors();

        //Bad request
        return $this->fail($error);
    }

    public function get_line_list($id)
    {
        $payload = $this->model->get_data($id);

        if ($payload == null) {
            return $this->failNotFound();
        }

        if (!$this->model->can_read($payload)) {
            return $this->failForbidden();
        }

        $data_list = $this->line_model->get_list($id);

        return $this->respond($data_list);
    }

    public function line_create()
    {
        $payload = $this->request->getJSON(true);
        $validator = Services::validation();

        $data_validation = array(

            'quotation_id' => array(
                'label' => 'quotation_id',
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
            'qte' => array(
                'label' => lang('STD.std_qte'),
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_field_required_err')
                )
            ),
            'price_unit_ht' => array(
                'label' => lang('STD.std_unit_price'),
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
            $id = $payload['quotation_id'];
            $model = $this->model->find($id);

            if ($model == null) {
                return $this->failNotFound();
            }

            if (!$this->model->is_deletable($model)) {
                return $this->failForbidden();
            }

            $designation = $payload['designation'];
            $qte = $payload['qte'];
            $price_unit_ht = $payload['price_unit_ht'];
            $reduction = dot_array_search('reduction', $payload);

            $id = $this->line_model->insert(array(
                'quotation_id' => $id,
                'user_id' => $this->session->user_identity['id'],
                'designation' => $designation,
                'qte' => $qte,
                'price_unit_ht' => $price_unit_ht,
                'reduction' => $reduction,
            ));

            $line = $this->line_model->find($id);

            return $this->respondCreated($line);
        }

        $error = $validator->getErrors();

        //Bad request
        return $this->fail($error);
    }

    public function line_update()
    {
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
            'qte' => array(
                'label' => lang('STD.std_qte'),
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_field_required_err')
                )
            ),
            'price_unit_ht' => array(
                'label' => lang('STD.std_unit_price'),
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
            $line = $this->line_model->find($id);

            if ($line == null) {
                return $this->failNotFound();
            }

            $model = $this->model->find($line['quotation_id']);

            if (!$this->model->is_editable($model)) {
                return $this->failForbidden();
            }

            $designation = $payload['designation'];
            $qte = $payload['qte'];
            $price_unit_ht = $payload['price_unit_ht'];
            $reduction = dot_array_search('reduction', $payload);

            $this->line_model
                            ->where('id', $id)
                            ->set(array(
                                'user_id' => $this->session->user_identity['id'],
                                'designation' => $designation,
                                'qte' => $qte,
                                'price_unit_ht' => $price_unit_ht,
                                'reduction' => $reduction,
                            ))
                            ->update();

            return $this->respondCreated();
        }

        $error = $validator->getErrors();

        //Bad request
        return $this->fail($error);
    }

    public function line_delete()
    {
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

        );

        $result = $validator->setRules($data_validation)
                            ->run($payload);

        if ($result)
        {
            $id = $payload['id'];
            $line = $this->line_model->find($id);

            if ($line == null) {
                return $this->failNotFound();
            }

            $model = $this->model->find($line['quotation_id']);

            if (!$this->model->is_deletable($model)) {
                return $this->failForbidden();
            }

            $this->line_model
                            ->where('id', $id)
                            ->delete();

            return $this->respondNoContent();
        }

        $error = $validator->getErrors();

        //Bad request
        return $this->fail($error);
    }

    public function get_data_structure()
    {
        $data = $this->model->get_data_structure();
        return $this->respond($data, 200);
    }

    public function get_role_authorization()
    {
        $data = array(
            'create' => array(
                'value' => true,
                'label' => lang('STD.std_create'),
            ),
            'read' => array(
                'value' => true,
                'label' => lang('STD.std_see_for_all_user'),
            ),
            'update' => array(
                'value' => true,
                'label' => lang('STD.std_update_for_all_user'),
            ),
            // 'delete' => array(
            //     'value' => true,
            //     'label' => lang('STD.std_delete_for_all_user'),
            // ),
        );

        return $this->respond($data);
    }

    public function get_order_list()
    {
        $data = array(
            array('label' => lang('STD.std_name'), 'value' => 'name'),
            array('label' => lang('STD.std_date'), 'value' => 'created_at'),
            array('label' => lang('STD.std_state'), 'value' => 'state_id', 'className' => 'ml-auto'),
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