<?php 

namespace App\Models\Mzara\Product;

use CodeIgniter\Model;
use App\Models\Mzara\Shop\ShopModel;
use App\Models\Mzara\Product\ProductTagModel;
use App\Models\Mzara\LoveEntityModel;

class ProductModel extends Model
{
    protected $table      = 'product';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = array(
        'user_id',
        'product_category_id',
        'gallery_id',
        'tag_link',
        'name',
        'price_before',
        'price',
        'description',
        'extra_table',
        'is_visible',
    );

    protected $useTimestamps = true;

    protected $useSoftDeletes = true;

    private $session;

    public function set_session($session)
    {
        $this->session = $session;
    }

    public function _find($id)
    {
        $model = $this->get_list_cursor('', true)->where('product.id', $id)
                        ->findAll();

        //tag_link
        if ($model == null) {
            $model = $this->get_list_cursor('', true)->where('product.tag_link', $id)
                            ->findAll();
        }

        if ($model != null) {
            $model = $model[0];
            $love_model = new LoveEntityModel();

            $model = $this->write_accessibility($model);
            $model['tag_list'] = explode(',', $model['tag_list']);
            $model['is_loved'] = $love_model->is_loved('product', $model['id'], $this->session->user_identity['id']);
        }

        return $model;
    }

    public function get_list_cursor($key='', $all = null)
    {
        $cursor = $this->select(
                        array(
                            'product.id',
                            'product.user_id',
                            'product.name',
                            'product.tag_link',
                            'product.gallery_id',
                            'product.price',
                            'product.price_before',
                            'product.is_visible',
                            'product_category_id',
                            'product_category.designation AS product_category',
                            'product_category.tag_name AS product_category_tag_name',
                            'product_category.comments AS product_category_comments',
                            'product.created_at',
                            $this->get_image_list_sql() . ' AS image_list',
                            $this->get_image_sql() . ' AS image_id',
                        ))
                    ->join('product_category', 'product.product_category_id=product_category.id', 'LEFT OUTER')
                    ->groupStart()
                    ->where('product.tag_link !=', '')
                    ->groupEnd()
                    ->groupStart()
                    ->like('lower(product.name)', strtolower($key))
                    ->groupEnd();

        if ($all == null) {
            $cursor->where('product.is_visible', true);
        }

        return $cursor;
    }

    public function get_list($payload)
    {
        $all = $payload['all'];

        $cursor = $this->get_list_cursor($payload['key'], $all);

        if ($payload['product_category_id'] != null && $payload['product_category_id'] != 0) {
            $cursor->where('product_category_id', $payload['product_category_id']);
        }

        /**
         * Sorting
         */
        if ($payload['order_by'] == null) {
            $payload['order_by'] = 'created_at';
        }

        $_order_by = '';

        if ($payload['order_by'] != 'created_at') {
            $_order_by .= $payload['order_by'];
        }

        if ($payload['product_category_id'] != null) {
            // $_order_by .= ',';
            $_order_by .= $this->get_product_category_visit_nb_sql();
        }
        
        if ($payload['order_by'] == 'created_at') {
            // $_order_by .= ',';
            $_order_by .= $payload['order_by'];
        }

        $data_list = $cursor->orderBy($_order_by, $payload['sort'])
                            ->findAll($payload['limit'], $payload['offset']);

        if ($data_list == null) {
            return array();
        }

        //init accessibility properties
        foreach ($data_list as $key => $data) {
            $data_list[$key] = $this->write_accessibility($data);
        }

        return $data_list;
    }

    public function get_count($payload)
    {
        $all = $payload['all'];

        $cursor = $this->get_list_cursor($payload['key'], $all);

        if ($payload['product_category_id'] != null && $payload['product_category_id'] != 0) {
            $cursor->where('product_category_id', $payload['product_category_id']);
        }
        return $cursor->countAllResults();
    }

    public function get_escaped_tag_link($name)
    {
        helper('string');
        $tag_link = get_tag_view($name);

        $product = $this->where('tag_link', $tag_link)->first();
        // user_name already used
        if ($product != null) {

            $last_number = $this->like('tag_link', $tag_link)
                ->countAllResults();
            $tag_link .= '-'. $last_number;
        }

        return $tag_link;
    }

    public function get_tag_list($product_id)
    {
        $product_tag_model = new ProductTagModel();

        return $product_tag_model->select(array(
                                'tag_id AS value',
                                'tag.designation AS label',
                            ))
                        ->join('tag', 'tag.id=tag_id', 'LEFT')
                        ->where('product_id', $product_id)
                        ->findAll();
    }

    public function get_love_nb_sql()
    {
        return '( SELECT COUNT(*) FROM love_entity WHERE love_entity.entity_type="product" AND love_entity.entity_id=product.id )';
    }

    public function get_image_sql()
    {
        return '( SELECT image.id FROM image' .
                ' INNER JOIN gallery ON gallery.id=image.gallery_id' .
                ' WHERE product.gallery_id = gallery.id ' .
                ' LIMIT 1 )';
    }

    public function get_image_list_sql()
    {
        return '( SELECT GROUP_CONCAT(image.id SEPARATOR ",") FROM image' .
                    ' INNER JOIN gallery ON gallery.id=image.gallery_id' .
                    ' WHERE product.gallery_id = gallery.id )';
    }

    public function get_tag_list_sql()
    {
        return '( SELECT GROUP_CONCAT(product_tag.tag_id SEPARATOR ",") FROM product_tag' .
                    ' WHERE product_tag.product_id = product.id )';
    }

    public function get_product_category_visit_nb_sql()
    {
        return '( SELECT COUNT(*) FROM log WHERE log.entity_type="product_category" AND log.entity_id=product.product_category_id )';
    }

    public function get_visit_nb_sql()
    {
        return '( SELECT COUNT(*) FROM log WHERE log.entity_type="product" AND log.entity_id=product.id )';
    }

    public function get_not_loved_sql()
    {
        return '( SELECT COUNT(*) FROM love_entity WHERE love_entity.entity_type="product" AND love_entity.entity_id=product.id AND love_entity.user_id="'. $this->session->user_indetity['id'] .'")';
    }

    public function write_accessibility($data)
    {
        $data['is_editable'] = $this->is_editable($data);
        $data['is_deletable'] = $this->is_deletable($data);
        $data['can_read'] = $this->can_read($data);
        return $data;
    }

    public function get_edit_form($model)
    {
        return array(
            'title' => lang('STD.std_product'),
            'sub_title' => lang('STD.std_product_basic_info_description'),
            'data_list' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id',
                    'id' => 'id',
                    'value' => $model['id'],
                ),
                array(
                    'type' => $model['shop_id'] != null ? 'hidden' : 'select',
                    'name' => 'shop_id',
                    'id' => 'shop_id',
                    'label' => lang('STD.std_shop'),
                    'placeholder' => lang('STD.std_shop'),
                    'option_link' => site_url('shop/select/product?order_by=designation&sort=asc'),
                    'value' => $model['shop_id'],
                    'is_required' => true,
                ),
                array(
                    'type' => 'text',
                    'name' => 'name',
                    'id' => 'name',
                    'label' => lang('STD.std_name'),
                    'placeholder' => lang('STD.std_name'),
                    'value' => $model['name'],
                    'is_required' => true,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'description',
                    'id' => 'description',
                    'label' => lang('STD.std_description'),
                    'placeholder' => lang('STD.std_shop_description'),
                    'value' => $model['description'],
                    'is_autosize' => true
                ),
                array(
                    'type' => 'select',
                    'name' => 'product_category_id',
                    'id' => 'product_category_id',
                    'label' => lang('STD.std_product_category'),
                    'placeholder' => lang('STD.std_product_category'),
                    'option_link' => site_url('product/select/category?order_by=designation&sort=asc'),
                    'value' => $model['product_brand_id'],
                    'is_required' => true,
                ),
                array(
                    'type' => 'select',
                    'name' => 'product_brand_id',
                    'id' => 'product_brand_id',
                    'label' => lang('STD.std_product_brand'),
                    'placeholder' => lang('STD.std_product_brand'),
                    'option_link' => site_url('product/select/brand?order_by=designation&sort=asc'),
                    'value' => $model['product_brand_id'],
                ),
                array(
                    'type' => 'numeric',
                    'name' => 'price_before',
                    'id' => 'price_before',
                    'label' => lang('STD.std_price_before'),
                    'placeholder' => lang('STD.std_price_before'),
                    'value' => $model['price_before']
                ),
                array(
                    'type' => 'numeric',
                    'name' => 'price',
                    'id' => 'price',
                    'label' => lang('STD.std_price'),
                    'placeholder' => lang('STD.std_price'),
                    'value' => $model['price']
                ),
            )
        );
    }

    public function get_default_edit_canvas()
    {
        return array(
            'id' => null,
            'shop_id' => null,
            'name' => '',
            'description' => '',
            'price_before' => 0,
            'price' => 0
        );
    }

    public function get_setting_form($model)
    {
        return array(
            'title' => '',
            'sub_title' => '',
            'auto_submit' => true,
            'data_list' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id',
                    'id' => 'id',
                    'value' => $model['id'],
                ),
                array(
                    'type' => 'select',
                    'name' => 'tag_list',
                    'id' => 'tag_list',
                    'is_select_multiple' => true,
                    'label' => lang('STD.std_tag'),
                    'placeholder' => lang('STD.std_product_tag_description'),
                    'option_link' => site_url('tag/select?order_by=designation&sort=asc'),
                    'action_link' => site_url('tag'),
                    'value' => $model['tag_list']
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'is_visible',
                    'id' => 'is_visible',
                    'label' => lang('STD.std_visibility'),
                    'placeholder' => lang('STD.std_product_visibility_description'),
                    'value' => $model['is_visible']
                ),
            )
        );
    }

    public function get_tag_form($model)
    {
        return array(
            'title' => '',
            'sub_title' => '',
            'auto_submit' => true,
            'data_list' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id',
                    'id' => 'id',
                    'value' => $model['id'],
                ),
                
            )
        );
    }

    public function can_read($model)
    {
        //Todo : return false if banned from shop or innapropriate for age
        return true;
    }

    public function can_create()
    {
        return $this->session->user_identity['id'] != null;
    }

    public function is_editable($model)
    {
        if ($model == null) {
            return false;
        }

        return true;

        $shop_model = new ShopModel();
        $shop_model->set_session($this->session);

        $shop = $shop_model->find($model['shop_id']);

        return $shop_model->can_create_product($shop);
    }

    public function is_deletable($model)
    {
        return $this->is_editable($model);
    }

}