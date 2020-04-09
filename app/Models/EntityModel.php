<?php 
namespace App\Models;

use CodeIgniter\Model;

class EntityModel extends Model
{
    protected $table      = '';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [];
    protected $useTimestamps = false;

    private $entity = '';

    private $simple_entity = array();

    private $entity_list = array();

    private $session;

    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);

        //init_entity_list
        $this->set_simple_entity();
        $this->entity_list['payement_mode'] = $this->simple_entity;
        $this->entity_list['payement_condition'] = $this->simple_entity;
    }

    public function set_simple_entity()
    {
        $this->simple_entity = array(

            'user_time_stamps' => false,
            'allowed_fields' => array('designation', 'comments'),
            'list' => array(
                //designation
                array(
                    'class' => 'col-8 col-sm-6 col-md-1 col-lg-4',
                    'url' => site_url('entity/{{entity}}/{{id}}'),
                    'index' => 'designation'
                ),
                //comments
                array(
                    'class' => 'col-4 col-sm-6 col-lg-8',
                    'index' => 'comments'
                ),
            ),
            'form' => array(
                'title' => '',
                'data_list' => array(
                    array(
                        'type' => 'text',
                        'name' => 'designation',
                        'id' => 'designation',
                        'label' => lang('STD.std_designation'),
                        'is_required' => true,
                        'placeholder' => lang('STD.std_designation'),
                    ),
                    array(
                        'type' => 'textarea',
                        'name' => 'comments',
                        'id' => 'comments',
                        'label' => lang('STD.std_comments'),
                        'is_autosize' => true,
                        'placeholder' => lang('STD.std_comments'),
                    ),
                    array(
                        'type' => 'hidden',
                        'extra' => 'command',
                        'data_list' => array(
                            array('type' => 'edit', 'condition' => '{{is_editable}}'),
                            array('type' => 'delete', 'condition' => '{{is_deletable}}')
                        )
                    ),
                ),
                
            ),
            'validation' => array(
                'designation' => array(
                    'label' => lang('STD.std_designation'),
                    'rules'  => 'required',
                    'errors' => array(
                        'required' => lang('STD.std_field_required_err')
                    )
                )
            ),
            'order_list' => array(
                array('label' => lang('STD.std_name'), 'value' => 'designation'),
                array('label' => lang('STD.std_date'), 'value' => 'created_at'),
            ),
            'select_list' => array( 'label' => 'designation', 'value' => 'id' ),
        
        );
    }

    public function set_session($session)
    {
        $this->session = $session;
    }

    public function set_entity($entity)
    {
        if (!isset($this->entity_list[$entity])) {
            return false;
        }

        $this->entity = $entity;

        $entity = $this->entity_list[$entity];
        $this->table = $this->entity;
        $this->allowedFields = $entity['allowed_fields'];
        $this->useTimestamps = $entity['user_time_stamps'];

        return true;
    }

    //Todo : get_list
    //get_entity_metadata
    //
    public function get_list_cursor($key='')
    {
        $cursor = $this->select('*');
        $header_list = $this->get_header_list();

        foreach ($header_list as $_key => $header) {
            if ($_key == 0) {
                $cursor->like('lower('. $header['index'] .')', strtolower($key));
            }else{
                $cursor->orLike('lower('. $header['index'] .')', strtolower($key));
            }
        }

        return $cursor;
    }

    public function get_list($key='', $order_by = 'id', $sort = 'desc', $nb = 10, $debut = 0)
    {
        $data_list = $this->get_list_cursor($key)
                    ->orderBy($order_by, $sort)
                    ->findAll($nb, $debut);

        //Todo : set access restriction here

        if ($data_list == null) {
            return array();
        }

        //init accessibility properties
        foreach ($data_list as $key => $data) {
            $data_list[$key] = $this->write_accessibility($data);
        }

        return $data_list;
    }

    public function get_count($key='')
    {
        return $this->get_list_cursor($key)
                    ->countAll();
    }

    public function get_select_list($label, $value)
    {
        $data_list = $this->select(
                        array(
                            $label . ' AS label',
                            $value . ' AS value'
                        ))
                    ->findAll();

        if ($data_list == null) {
            return array();
        }

        return $data_list;
    }

    public function get_entity_name()
    {
        return $this->entity;
    }

    public function get_entity($name)
    {
        return $this->entity_list[$name];
    }

    public function get_validation_rules()
    {
        return $this->entity_list[$this->entity]['validation'];
    }

    public function get_allowed_field()
    {
        return $this->entity_list[$this->entity]['allowed_fields'];
    }

    public function get_select_list_canvas()
    {
        return $this->entity_list[$this->entity]['select_list'];
    }

    public function get_parsed_payload($_payload)
    {
        $allowed_field_list = $this->get_allowed_field();
        $payload = array();

        foreach ($allowed_field_list as $value) {
            if (isset($_payload[$value])) {
                $payload[$value] = $_payload[$value];
            }
        }

        return $payload;
    }

    public function get_order_list()
    {
        return $this->entity_list[$this->entity]['order_list'];
    }

    public function get_access($value='')
    {
        return array(
            'can_create' => $this->can_create()
        );
    }

    public function get_header_list()
    {
        return $this->entity_list[$this->entity]['list'];
    }

    public function get_form()
    {
        return $this->entity_list[$this->entity]['form'];
    }

    public function write_accessibility($data)
    {
        $data['is_editable'] = $this->is_editable($data);
        $data['is_deletable'] = $this->is_deletable($data);
        // $data['can_read'] = $this->can_read($data);
        return $data;
    }

    public function can_create()
    {
        if ($this->session->is_superadmin()) {
            return true;
        }

        return $this->session->is_authorized($this->entity, 'create', true);
    }

    public function can_read()
    {
        if ($this->session->is_superadmin()) {
            return true;
        }

        return $this->session->is_authorized($this->entity, 'read', true);
    }

    public function is_editable($model)
    {
        if ($model == null) {
            return false;
        }

        if ($this->session->is_superadmin()) {
            return true;
        }

        return $this->session->is_authorized($this->entity, 'update', true);
    }

    public function is_deletable($model)
    {
        if ($model == null) {
            return false;
        }

        if ($this->session->is_superadmin()) {
            return true;
        }

        return $this->session->is_authorized($this->entity, 'delete', true);
    }
}