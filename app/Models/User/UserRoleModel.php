<?php 
namespace App\Models\User;

use CodeIgniter\Model;
use App\Models\User\UserModel;
use App\Models\Quotation\QuotationModel;
use App\Models\Order\OrderModel;
use App\Models\DeliveryNote\DeliveryNoteModel;
use App\Models\Bill\BillModel;

class UserRoleModel extends Model
{
    protected $table      = 'user_role';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = array(
        'designation',
        'comments',
        'rules'
    );

    private $session;

    public function set_session($session)
    {
        $this->session = $session;
    }

    public function get_list($key, $order_by, $sort, $nb, $debut)
    {
        return $this->select('id, designation')
                    ->like('lower(designation)', strtolower($key))
                    ->orLike('lower(comments)', strtolower($key))
                    ->orderBy($order_by, $sort)
                    ->findAll($nb, $debut);
    }

    public static function get_role_autorization($rules, $module_name, $method, $value = null)
    {
        return isset($rules[$module_name][$method]);
    }

    public function get_argument()
    {
        //get accessibility
        $data_list = array(
            UserModel::get_role(),
            QuotationModel::get_role(),
            OrderModel::get_role(),
            DeliveryNoteModel::get_role(),
            BillModel::get_role(),
            
        );

        return $data_list;

    }

    public function can_create()
    {
        return $this->session->is_superadmin();
    }

    public function is_editable()
    {
        return $this->session->is_superadmin();
    }

    public function is_deletable()
    {
        return $this->session->is_superadmin();
    }
}