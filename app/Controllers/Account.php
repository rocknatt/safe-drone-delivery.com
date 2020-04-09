<?php 

namespace App\Controllers;

use App\Models\User\UserModel;
use App\Models\User\UserProfilModel;
use App\Models\ImageModel;
use App\Models\GalleryModel;
use CodeIgniter\API\ResponseTrait;

use CodeIgniter\Config\Services;

class Account extends BaseController
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

        $this->model = new UserProfilModel();
        $this->model->set_session($this->session);
    }

    public function index()
    {
        $key = $this->request->getGet('key');
        $order_by = $this->request->getGet('order_by');
        $sort = $this->request->getGet('sort');
        $nb = $this->request->getGet('nb');
        $debut = $this->request->getGet('debut');
        $user_category_id = $this->request->getGet('user_category_id');

        //Todo : authorized access
        $data_list = $this->model->get_list($key, $order_by, $sort, $nb, $debut, $user_category_id);

        return $this->respond($data_list);
    }

    public function create()
    {
        if (!$this->model->can_create_user()) {
            return $this->failForbidden();
        }

        $payload = $this->request->getJSON(true);
        $validator = Services::validation();
        $data_validation = array(

            'user_name' => array(
                'label' => lang('STD.std_user_name'),
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_required_err')
                )
            ),
            'email' => array(
                'label' => lang('STD.std_email'),
                'rules'  => 'required|valid_email',
                'errors' => array(
                    'required' => lang('STD.std_required_err'),
                    'valid_email' => lang('STD.std_email_valid_err'),
                )
            ),
            'password' => array(
                'label' => lang('STD.std_password'),
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_required_err')
                )
            )

        );

        $result = $validator->setRules($data_validation)
                            ->run($payload);

        $error = lang('STD.std_bad_payload');

        if ($result)
        {
            $user_name = $payload['user_name'];
            $email = $payload['email'];
            $password = $payload['password'];

            //validate user
            $model = new UserModel();
            $user = $model->find_user($email);

            if ($user == null) {
                $escaped_user_name = $model->get_escaped_user_name($user_name);
                $user_id = $model->create($escaped_user_name, $email, $password);

                $this->model->create($user_id, array(
                    'name' => $user_name,
                    'first_name' => '',
                ));

                return $this->respondCreated(array(
                    'id' => $user_id,
                ));
            }
            else{
                $error = lang('STD.std_email_user_alread_used');
            }
            
        }

        //Bad request
        return $this->fail($error);
    }

    public function create_client()
    {
        if (!$this->model->can_create_user()) {
            return $this->failForbidden();
        }

        $payload = $this->request->getJSON(true);
        $validator = Services::validation();
        $data_validation = array(

            'user_name' => array(
                'label' => lang('STD.std_user_name'),
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_required_err')
                )
            ),
            'user_category_id' => array(
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_required_err')
                )
            ),
            'email' => array(
                'label' => lang('STD.std_email'),
                'rules'  => 'required|valid_email',
                'errors' => array(
                    'required' => lang('STD.std_required_err'),
                    'valid_email' => lang('STD.std_email_valid_err'),
                )
            ),

        );

        $result = $validator->setRules($data_validation)
                            ->run($payload);

        if ($result)
        {
            $user_name = $payload['user_name'];
            $email = $payload['email'];
            $password = 'user';

            //validate user
            $model = new UserModel();
            $user = $model->find_user($email);

            if ($user == null) {
                $escaped_user_name = $model->get_escaped_user_name($user_name);
                $user_id = $model->create($escaped_user_name, $email, $password);
                $adress = dot_array_search('adress', $payload);
                $telephone = dot_array_search('telephone', $payload);
                $email = dot_array_search('email', $payload);
                $site_web = dot_array_search('site_web', $payload);
                $cin = dot_array_search('cin', $payload);
                $nif = dot_array_search('nif', $payload);
                $stat = dot_array_search('stat', $payload);
                $rcs = dot_array_search('rcs', $payload);
                $user_category_id = dot_array_search('user_category_id', $payload);

                $id = $this->model->create($user_id, array(
                    'name' => $user_name,
                    'first_name' => '',
                    'adress' => $adress,
                    'telephone' => $telephone,
                    'email' => $email,
                    'site_web' => $site_web,
                    'cin' => $cin,
                    'nif' => $nif,
                    'stat' => $stat,
                    'rcs' => $rcs,
                    'user_category_id' => $user_category_id,
                ));

                $payload = $this->model->find($id);
                $payload['label'] = $payload['view_name'];
                $payload['value'] = $id;

                return $this->respondCreated($payload);
            }
            else{
                $error = lang('STD.std_email_user_alread_used');
            }
            
        }

        $error = $validator->getErrors();

        //Bad request
        return $this->fail($error);
    }

    public function update()
    {
        $data = $this->request->getJSON(true);

        if (!isset($data['id'])) {
            $error = lang('STD.std_bad_payload');
            return $this->fail($error);
        }

        $id = $data['id'];

        $user = $this->model->find($id);

        if ($user == null) {
            return $this->failNotFound();
        }

        if (!$this->model->is_editable($user)) {
            return $this->failForbidden();
        }

        $adress = dot_array_search('adress', $data);
        $telephone = dot_array_search('telephone', $data);
        $email = dot_array_search('email', $data);
        $site_web = dot_array_search('site_web', $data);
        $initial = dot_array_search('initial', $data);
        $cin = dot_array_search('cin', $data);
        $nif = dot_array_search('nif', $data);
        $stat = dot_array_search('stat', $data);
        $rcs = dot_array_search('rcs', $data);

        $this->model
                ->where('id', $id)
                ->set(array(
                    'adress' => $adress,
                    'telephone' => $telephone,
                    'email' => $email,
                    'site_web' => $site_web,
                    'cin' => $cin,
                    'nif' => $nif,
                    'stat' => $stat,
                    'rcs' => $rcs,
                    'initial' => $initial
                ))
                ->update();

        return $this->respondCreated();
    }

    public function profil($id)
    {
        $user = $this->model->get_user_profil($id);

        if ($user == null) {
            return $this->failNotFound();
        }

        // Todo : data confidentiality check here
        return $this->respond($user);
    }

    public function image($id)
    {
        $user = $this->model->find($id);

        if ($user == null) {
            $id = 0;
        }

        $thumb_file = IMAGE_FOLDER_WRITABLE_PATH . '/user_'. $id .'.png';

        if (file_exists($thumb_file)) {

            $base64 = $this->request->getGet('base64');
            if ($base64 != null) {
                $data = file_get_contents($thumb_file);
                $base64 = 'data:image/png;base64,' . base64_encode($data);

                return $this->respond(array(
                    'base64' => $base64
                ));
            }

            return $this->return_file($thumb_file);
        }
        else{

            if ($user == null) {
                return $this->failNotFound();
            }

            if ($user['image_id'] == null) {
                $this->model->get_thumb($user);
            }
            else{
                $this->model->get_image_profil($id, $user['image_id']);
            }

            return $this->redirect('account/image/'. $id);
        }
    }

    public function upload_image($id)
    {
        $payload = $this->model->find($id);

        if ($payload == null) {
            return $this->failNotFound();
        }

        if (!$this->model->can_edit_image_profil($payload)) {
            return $this->failForbidden();
        }

        //check gallery for profil_picture, create one if not exist
        $gallery_model = new GalleryModel();
        $gallery = $gallery_model->where(array('user_id' => $this->session->user_identity['id'], 'name' => GalleryModel::$default_profil_picture_name))
                                ->first();
        if ($gallery == null) {
            $gallery_id = $gallery_model->insert(array(
                'user_id' => $this->session->user_identity['id'], 
                'name' => GalleryModel::$default_profil_picture_name
            ));
        }else{
            $gallery_id = $gallery['id'];
        }

        $image_model = new ImageModel();
        $image_id = $image_model->add($this->request->getFile('image_id'), $gallery_id);

        if ($image_id == null) {
            return $this->fail(lang('STD.std_invalid_file_err'));
        }

        $thumb_file = IMAGE_FOLDER_WRITABLE_PATH . '/user_' . $id . '.png';
        if (file_exists($thumb_file)) {
            unlink($thumb_file); //Force image_profil to refresh
        }

        $this->model->where('id', $id)
                ->set('image_id', $image_id)
                ->update();

        return $this->respondCreated(array(
            'img_url' => site_url('account/image/'. $id)
        ));
    }

    public function get_client_list()
    {
        //todo : Authorized access

        $data_list = $this->model->get_client_list();

        return $this->respond($data_list);
    }

    public function get_order_list()
    {
        $data = array(
            array('label' => lang('STD.std_name'), 'value' => 'name'),
            array('label' => lang('STD.std_date'), 'value' => 'date_inscription'),
            array('label' => lang('STD.std_role'), 'value' => 'user_role_id', 'className' => 'ml-auto'),
        );

        return $this->respond($data);
    }

    public function get_access()
    {
        $data = array(
            'can_create' => $this->model->can_create_user()
        );

        return $this->respond($data);
    }

    public function get_user_followed_list()
    {
        $nb = $this->request->getGet('nb');
        $debut = $this->request->getGet('debut');

        $data_list = $this->model->get_user_followed_list($debut, $nb);

        return $this->respond($data_list);
    }

    public function get_client_form($user_category_id)
    {
        $data = $this->model->get_client_form($user_category_id);

        return $this->respond($data);
    }

}