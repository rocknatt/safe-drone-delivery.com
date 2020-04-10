<?php 

namespace App\Models\Mzara\Product;

use CodeIgniter\Model;

class ProductTagModel extends Model
{
    protected $table      = 'product_tag';

    protected $returnType = 'array';

    protected $allowedFields = array(
    	'tag_id',
    	'product_id',
    );

}