<?php 

namespace App\Models\Mzara\Product;

use CodeIgniter\Model;

class ProductBrandModel extends Model
{
    protected $table      = 'product_brand';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = array(
    	'image_id',
    	'tag_name',
    	'designation',
    	'comments',
    );

    public function _find($id)
    {
    	$model = $this->find($id);

    	if ($model == null) {
    		$model = $this->where('tag_name', $id)
    					->first();
    	}

    	return $model;
    }

    public function get_list_cursor($key='')
    {
        $cursor = $this->select(
                        array(
                            'id',
                            'designation',
                            'comments',
                            'tag_name',
                        ))
                    ->groupStart()
                    ->like('lower(designation)', strtolower($key))
                    ->orLike('lower(comments)', strtolower($key))
                    ->groupEnd();

        return $cursor;
    }

    public function get_list($payload)
    {
        if ($payload['order_by'] == null) {
            $payload['order_by'] = 'designation';
        }

        $data_list = $this->get_list_cursor($payload['key'])
                                ->orderBy($payload['order_by'], $payload['sort'])
                                ->findAll($payload['limit'], $payload['offset']);

        if ($data_list == null) {
            return array();
        }

        return $data_list;
    }

    public function get_count($payload)
    {
        return $this->get_list_cursor($payload['key'])
                    ->countAll();
    }

}