<?php 

namespace App\Controllers;

use App\Models\Mzara\Product\ProductModel;
use App\Models\Mzara\Product\ProductCategoryModel;
use App\Models\Mzara\Product\ProductBrandModel;
use App\Models\Mzara\Product\ProductTagModel;
use App\Models\ImageModel;
use App\Models\GalleryModel;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

use CodeIgniter\Config\Services;

class Product extends BaseController
{
    use ResponseTrait;

    /**
     * controller default model
     * @var App\Models\Mzara\Product\ProductModel
     */
    private $model;

    /**
     * controller default model
     * @var App\Models\Mzara\Product\ProductCategoryModel
     */
    private $product_category_model;

    /**
     * controller default model
     * @var App\Models\Mzara\Product\ProductBrandModel
     */
    private $product_brand_model;

    /**
     * Constructor.
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $this->model = new ProductModel();
        $this->model->set_session($this->session);
        $this->product_category_model = new ProductCategoryModel();
        $this->product_brand_model = new ProductBrandModel();
        $this->current_entity = lang('STD.std_product');
    }

    public function index($product_category_id = null)
    {
        $product_category = null;
        if ($product_category_id != null) {
            $product_category = $this->product_category_model->_find($product_category_id);
        }

        $title = $this->get_title();
        
        if ($product_category != null) {
            $this->log->write('product_category', $product_category['id']);
            $title = $this->get_title($product_category['designation']);
        }

        // $this->log->write('product');

        return $this->respond(array(
            'product_category' => $product_category,
            'title' => $title
        ));
    }

    public function get_list()
    {
        $payload = $this->get_list_payload();
        $payload['product_category_id'] = $this->request->getGet('product_category_id');
        $payload['product_brand_id'] = $this->request->getGet('product_brand_id');
        $payload['shop_id'] = $this->request->getGet('shop_id');
        $payload['all'] = $this->request->getGet('all');

        //Todo : Logo product_brand here
        $data_list = $this->model->get_list($payload);
        $total = $this->model->get_count($payload);

        return $this->respond(array(
            'total' => $total,
            'count' => count($data_list),
            'page' => $payload['page'],
            'limit' => $payload['limit'],
            'data_list' => $data_list,
        ));
    }

    public function get_product_category_list()
    {
        $payload = $this->get_list_payload();

        $data_list = $this->product_category_model->get_list($payload);
        $total = $this->product_category_model->get_count($payload);

        return $this->respond(array(
            'total' => $total,
            'count' => count($data_list),
            'page' => $payload['page'],
            'limit' => $payload['limit'],
            'data_list' => $data_list,
        ));
    }

    public function get_product_category_select_list()
    {
        $payload = $this->get_list_payload();
        $data_list = $this->product_category_model->get_list($payload);

        $result = array();

        foreach ($data_list as $value) {
            array_push($result, array('id' => $value['id'], 'value' => $value['id'], 'label' => $value['designation']));
        }

        return $this->respond($result);
    }

    public function get_product_brand_select_list()
    {
        $payload = $this->get_list_payload();
        $data_list = $this->product_brand_model->get_list($payload);

        $result = array();

        foreach ($data_list as $value) {
            array_push($result, array('id' => $value['id'], 'value' => $value['id'], 'label' => $value['designation']));
        }

        return $this->respond($result);
    }

    public function get_product_brand_list()
    {
        $payload = $this->get_list_payload();

        $data_list = $this->product_brand->get_list($payload);
        $total = $this->product_brand->get_count($payload);

        return $this->respond(array(
            'total' => $total,
            'count' => count($data_list),
            'page' => $payload['page'],
            'limit' => $payload['limit'],
            'data_list' => $data_list,
        ));
    }

    public function get_suggest_list()
    {
        $payload = $this->get_list_payload();
        $payload['product_category_id'] = $this->request->getGet('product_category_id');
        $payload['product_brand_id'] = $this->request->getGet('product_brand_id');
        $payload['all'] = $this->request->getGet('all');

        $data_list = $this->model->get_suggest_list($payload);
        $total = $this->model->get_suggest_count($payload);

        return $this->respond(array(
            'total' => $total,
            'count' => count($data_list),
            'page' => $payload['page'],
            'limit' => $payload['limit'],
            'data_list' => $data_list,
        ));
    }

    public function create()
    {
        //Todo : make session handle "must_login_first" error, throw an error and end script
        if (!$this->model->can_create()) {
            return $this->failForbidden(array(
                'code' => 'must_login_first',
                'message' => ''
            ));
        }

        $payload = $this->request->getJSON(true);
        $validator = Services::validation();
        $data_validation = array(

            'shop_id' => array(
                'label' => lang('STD.std_shop'),
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_required_err'),
                )
            ),

        );

        $result = $validator->setRules($data_validation)
                            ->run($payload);

        if ($result)
        {
            $name = dot_array_search('name', $payload);
            $shop_id = $payload['shop_id'];
            $product_category_id = dot_array_search('product_category_id', $payload);
            $product_brand_id = dot_array_search('product_brand_id', $payload);
            $price_before = dot_array_search('price_before', $payload);
            $price = dot_array_search('price', $payload);
            $description = dot_array_search('description', $payload);

            $shop_model = new ShopModel();
            $shop_model->set_session($this->session);

            $shop = $shop_model->find($shop_id);

            if ($shop == null) {
                return $this->failNotFound();
            }

            if (!$shop_model->can_create_product($shop)) {
                return $this->failForbidden();
            }

            $gallery_model = new GalleryModel();
            $gallery_id = $gallery_model->insert(array(
                'user_id' => $this->session->user_identity['id'], 
                'name' => GalleryModel::$default_profil_picture_name
            ));

            $data = array(
                'user_id' => $this->session->user_identity['id'],
                'shop_id' => $shop_id,
                'gallery_id' => $gallery_id,
                'name' => '',
                'is_visible' => false,
            );

            if ($name != null) {
                $data['name'] = $name;
                $data['tag_link'] = $this->model->get_escaped_tag_link($name);
                $data['is_visible'] = true;
            }

            if ($product_category_id != null) {
                $data['product_category_id'] = $product_category_id;
            }

            if ($product_brand_id != null) {
                $data['product_brand_id'] = $product_brand_id;
            }

            if ($price_before != null) {
                $data['price_before'] = $price_before;
            }

            if ($price != null) {
                $data['price'] = $price;
            }

            if ($description != null) {
                $data['description'] = $description;
            }

            $id = $this->model->insert($data);

            $payload = $this->model->_find($id);

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
            // 'name' => array(
            //     'label' => lang('STD.std_name'),
            //     'rules'  => 'required',
            //     'errors' => array(
            //         'required' => lang('STD.std_required_err')
            //     )
            // ),
            // 'shop_id' => array(
            //     'label' => lang('STD.std_shop'),
            //     'rules'  => 'required',
            //     'errors' => array(
            //         'required' => lang('STD.std_required_err'),
            //     )
            // ),
            'product_category_id' => array(
                'label' => lang('STD.std_product_category'),
                'rules'  => 'if_exist',
                'errors' => array(
                    'if_exist' => lang('STD.std_required_err'),
                )
            ),
            'product_brand_id' => array(
                'label' => lang('STD.std_product_brand'),
                'rules'  => 'if_exist',
                'errors' => array(
                    'if_exist' => lang('STD.std_required_err'),
                )
            ),
            'price_before' => array(
                'label' => 'price_before',
                'rules'  => 'if_exist',
                'errors' => array(
                    'if_exist' => lang('STD.std_required_err'),
                )
            ),
            'price' => array(
                'label' => 'price',
                'rules'  => 'if_exist',
                'errors' => array(
                    'if_exist' => lang('STD.std_required_err'),
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

            $name = dot_array_search('name', $payload);
            $shop_id = dot_array_search('shop_id', $payload);
            $product_category_id = dot_array_search('product_category_id', $payload);
            $product_brand_id = dot_array_search('product_brand_id', $payload);
            $price_before = dot_array_search('price_before', $payload);
            $price = dot_array_search('price', $payload);
            $description = dot_array_search('description', $payload);
            $extra_table = dot_array_search('extra_table', $payload);
            $tag_list = dot_array_search('tag_list', $payload);
            $is_visible = dot_array_search('is_visible', $payload);

            $data = array();

            if ($name != null) {
                $data['name'] = $name;
            }

            if ($description != null) {
                $data['description'] = $description;
            }

            if ($shop_id != null) {
                $data['shop_id'] = $shop_id;
            }

            if ($product_category_id != null) {
                $data['product_category_id'] = $product_category_id;
            }

            if ($product_brand_id != null) {
                $data['product_brand_id'] = $product_brand_id;
            }

            if ($price_before != null) {
                $data['price_before'] = $price_before;
            }

            if ($price != null) {
                $data['price'] = $price;
            }

            if ($description != null) {
                $data['description'] = $description;
            }

            if ($model['name'] == null) {
                $data['tag_link'] = $this->model->get_escaped_tag_link($name);
                $data['is_visible'] = true;
            }

            if ($extra_table != null) {
                $data['extra_table'] = $extra_table;
            }

            if ($is_visible != null) {
                $data['is_visible'] = $is_visible;
            }

            if ($tag_list != null) {

                $product_tag_model = new ProductTagModel();
                $product_tag_model->where('product_id', $id)
                                    ->delete();

                foreach ($tag_list as $tag_id) {
                    $product_tag_model->insert(array(
                        'product_id' => $id,
                        'tag_id' => $tag_id
                    ));
                }

            }

            if ($data != null) {
                $this->model->where('id', $id)
                    ->set($data)
                    ->update();
            }

            return $this->respondCreated();
        }

        $error = $validator->getErrors();

        //Bad request
        return $this->fail($error);
    }

    public function read($id)
    {
        $payload = $this->model->_find($id);

        if ($payload == null) {
            return $this->failNotFound();
        }

        if (!$this->model->can_read($payload)) {
            return $this->failForbidden();
        }

        // $payload['extra_table'] = json_decode($payload['extra_table']);

        /**
         * check tag_list
         */
        // $payload['tag_list_option'] = $this->model->get_tag_list($payload['id']);

        // $this->log->write('product', $id);

        $title = $this->get_title($payload['name']);

        return $this->respond(array(
            'title' => $title,
            'data' => $payload
        ));
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

    public function love()
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

            $love_model = new LoveEntityModel();
            $result = $love_model->toggle('product', $id, $this->session->user_identity['id']);

            //love_shop
            $this->hub->push_message('love_product', array('id' => $model['id'], 'count' => $result['count']));

            //notification
            $user_to_notify_list = array();
            $shop_user_model = new ShopUserModel();

            $user_list = $shop_user_model->where('shop_id', $model['shop_id'])
                                        ->findAll();

            foreach ($user_list as $data) {
                array_push($user_to_notify_list, $data['user_id']);
            }

            $link = 'product/'. $model['id'];

            $this->user_notification->create_love_notification('product', $model['id'], $user_to_notify_list, $link);

            return $this->respondCreated($result);
        }

        $error = $validator->getErrors();

        //Bad request
        return $this->fail($error);
    }

    public function upload_image()
    {
        $data_validation = array(

            'id' => array(
                'label' => 'id',
                'rules'  => 'required',
                'errors' => array(
                    'required' => lang('STD.std_field_required_err')
                )
            ),

        );

        if ($this->validate($data_validation))
        {
            $id = $this->request->getPost('id');
            $model = $this->model->find($id);

            if ($model == null) {
                return $this->failNotFound();
            }

            if (!$this->model->is_editable($model)) {
                return $this->failForbidden();
            }

            //check gallery for profil_picture, create one if not exist
            if ($model['gallery_id'] == null) {

                $gallery_model = new GalleryModel();
                $gallery_id = $gallery_model->insert(array(
                    'user_id' => $this->session->user_identity['id'], 
                    'name' => GalleryModel::$default_profil_picture_name
                ));

                $model['gallery_id'] = $gallery_id;
                $this->model->where('id', $id)
                            ->set('gallery_id', $gallery_id)
                            ->update();
            }


            $image_model = new ImageModel();
            $image_id = $image_model->add($this->request->getFile('image_id'), $model['gallery_id']);

            if ($image_id == null) {
                return $this->fail(lang('STD.std_invalid_file_err'));
            }

            return $this->respondCreated(array(
                'id' => $image_id,
                'th_url' => site_url('image/'. $image_id . '?prefix=ths'),
                'url' => site_url('image/'. $image_id),
                'img_url' => site_url('image/'. $image_id),
                'image_id' => $image_id,
                'gallery_id' => $model['gallery_id']
            ));
        }

        $error = $validator->getErrors();

        //Bad request
        return $this->fail($error);
    }

    public function delete_image()
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
            'image_id' => array(
                'label' => 'image_id',
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
            $image_id = $payload['image_id'];
            $model = $this->model->find($id);

            if ($model == null) {
                return $this->failNotFound();
            }

            if (!$this->model->is_editable($model)) {
                return $this->failForbidden();
            }


            $image_model = new ImageModel();
            $image_model->_delete($image_id);

            return $this->respondNoContent();
        }

        $error = $validator->getErrors();

        //Bad request
        return $this->fail($error);
    }

    public function get_tag_list()
    {
        $payload = $this->get_list_payload();
        $tag_model = new TagModel();

        $data_list = $tag_model->get_list($payload);
        $total = $tag_model->get_count($payload);

        return $this->respond(array(
            'total' => $total,
            'count' => count($data_list),
            'page' => $payload['page'],
            'limit' => $payload['limit'],
            'data_list' => $data_list,
        ));
    }

    public function get_tag_select_list()
    {
        $payload = $this->get_list_payload();
        $tag_model = new TagModel();

        $data_list = $tag_model->get_list($payload);

        $result = array();

        foreach ($data_list as $value) {
            array_push($result, array('id' => $value['id'], 'label' => $value['designation']));
        }

        return $this->respond($result);
    }

    public function get_user_product_select_list()
    {
        $payload = $this->get_list_payload();
        $payload['product_category_id'] = $this->request->getGet('product_category_id');
        $payload['product_brand_id'] = $this->request->getGet('product_brand_id');
        $payload['user_id'] = $this->session->user_identity['id'];
        $payload['all'] = true;

        //Todo : Logo product_brand here
        $data_list = $this->model->get_list($payload);

        $result = array();

        foreach ($data_list as $value) {
            array_push($result, array('id' => $value['id'], 'label' => $value['name']));
        }

        return $this->respond($result);
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
            'can_create' => $this->model->can_create()
        );

        return $this->respond($data);
    }

    public function get_form()
    {
        $data = $this->model->get_data_structure();
        return $this->respond($data, 200);
    }

    public function get_edit_form($id = null)
    {
        if ($id == null) {

            $data = $this->model->get_edit_form($this->model->get_default_edit_canvas());
            $data['image_list'] = '';
            return $this->respond($data, 200);
        }

        $model = $this->model->_find($id);

        if ($model == null) {
            return $this->failNotFound();
        }

        if (!$this->model->is_editable($model)) {
            return $this->failForbidden();
        }

        $data = $this->model->get_edit_form($model);
        $data['image_list'] = $model['image_list'];
        return $this->respond($data, 200);

    }

    public function get_setting_form($id = null)
    {
        $model = $this->model->_find($id);

        if ($model == null) {
            return $this->failNotFound();
        }

        if (!$this->model->is_editable($model)) {
            return $this->failForbidden();
        }

        $data = $this->model->get_setting_form($model);
        return $this->respond($data, 200);
    }

    // public function get_tag_form($id)
    // {
    //     $model = $this->model->_find($id);

    //     if ($model == null) {
    //         return $this->failNotFound();
    //     }

    //     if (!$this->model->is_editable($model)) {
    //         return $this->failForbidden();
    //     }

    //     $data = $this->model->get_tag_form($model);
    //     return $this->respond($data, 200);
    // }

}