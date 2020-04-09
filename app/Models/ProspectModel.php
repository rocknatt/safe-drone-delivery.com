<?php 
namespace App\Models;

use CodeIgniter\Model;

class ProspectModel extends Model
{
    protected $table      = 'prospect';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = array(
    	'user_id',
    	'user_client_id',
		'title',
		'description',
		'interlocuter',
		'report',
		'state_id',
    );

    protected $useTimestamps = true;

    public $session;

    public function set_session($session)
    {
    	$this->session = $session;
    }

    public function get_list($key='', $order_by = 'create_at', $sort = 'desc', $nb = 10, $debut = 0)
    {
    	$data_list = $this->select(
    					array(
    						'prospect.id',
    						'user_id',
    						'user_client_id',
    						'user_profil.view_name AS user_name',
    						'user_profil_client.view_name AS user_client_name',
    						'title',
    						'description',
    						'state_id',
    						'created_at',
    						'updated_at',
    					))
    				->join('user_profil', 'prospect.user_id=user_profil.id', 'LEFT')
    				->join('user_profil AS user_profil_client', 'prospect.user_client_id=user_profil_client.id', 'LEFT OUTER')
    				->join('state', 'prospect.state_id=state.id', 'LEFT')
    				->like('lower(title)', strtolower($key))
                    ->orLike('lower(interlocuter)', strtolower($key))
                    ->orLike('lower(description)', strtolower($key))
                    ->orLike('lower(report)', strtolower($key))
    				->orderBy($order_by, $sort)
                    ->findAll($nb, $debut);

    	if ($data_list == null) {
    		return array();
    	}

    	//init accessibility properties
    	foreach ($data_list as $key => $data) {
    		$data_list[$key] = $this->write_accessibility($data);
    	}

    	return $data_list;
    }

    public function get_prospect($id)
    {
    	$data = $this->select(
    					array(
    						'prospect.id',
    						'user_id',
    						'user_client_id',
    						'user_profil.view_name AS user_name',
    						'user_profil_client.view_name AS user_client_name',
    						'title',
    						'description',
    						'interlocuter',
    						'report',
    						'state_id',
    						'state.designation AS state',
    						'created_at',
    						'updated_at',
    					))
    				->join('user_profil', 'prospect.user_id=user_profil.id', 'LEFT')
    				->join('user_profil AS user_profil_client', 'prospect.user_client_id=user_profil_client.id', 'LEFT OUTER')
    				->join('state', 'prospect.state_id=state.id', 'LEFT')
    				->where('prospect.id', $id)
    				->first();

    	if ($data != null) {
    		$data = $this->write_accessibility($data);
    	}

    	return $data;
    }

    public function write_accessibility($data)
    {
    	$data['is_editable'] = $this->is_editable($data);
    	$data['is_deletable'] = $this->is_deletable($data);
    	$data['can_read'] = $this->can_read($data);
    	$data['can_validate'] = $this->can_validate($data);

    	return $data;
    }

    public function can_create()
    {
    	if ($this->session->is_superadmin()) {
            return true;
        }

        return $this->session->is_authorized('prospect', 'create', true);
    }

    public function can_read($prospect)
    {
    	if ($prospect == null) {
            return false;
        }

        if ($this->session->is_superadmin()) {
            return true;
        }

        if ($prospect['user_id'] == $this->session->user_identity['id']) {
            return true;
        }

        //Todo : read prospect only inside group role

        return $this->session->is_authorized('prospect', 'read', true);
    }

    public function can_access_list()
    {
    	if ($this->session->is_superadmin()) {
            return true;
        }

    	return $this->session->is_authorized('prospect', 'read', true) || $this->session->is_authorized('prospect', 'create', true);
    }

    public function can_validate($prospect)
    {
    	if ($prospect == null) {
            return false;
        }

        if ($this->session->is_superadmin()) {
            return true;
        }

        return $prospect['user_id'] == $this->session->user_identity['id'];
    }

    public function is_editable($prospect)
    {
        if ($prospect == null) {
            return false;
        }

        if ($this->session->is_superadmin()) {
            return true;
        }

        if ($prospect['user_id'] == $this->session->user_identity['id']) {
            return true;
        }

        return $this->session->is_authorized('prospect', 'update', true);
    }

    public function is_deletable($prospect)
    {
        if ($prospect == null) {
            return false;
        }

        if ($this->session->is_superadmin()) {
            return true;
        }

        if ($prospect['user_id'] == $this->session->user_identity['id']) {
            return true;
        }

        return $this->session->is_authorized('prospect', 'delete()', true);
    }
}