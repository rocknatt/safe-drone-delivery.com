<?php 

namespace App\Models\Command;

use CodeIgniter\Model;
use App\Models\StateModel;
use CodeIgniter\I18n\Time;

class CommandModel extends Model
{
    protected $table      = 'command';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = array(
        'num',
        'user_id',
        'state_id',
        'name',
        'phone_number',
        'adress',
        'email',
        'longitude',
        'latitude',
        'altitude',
        'state_id',
    );

    protected $useTimestamps = true;

    public $session;

    public function set_session($session)
    {
        $this->session = $session;
    }

    public function get_list_cursor($key='')
    {
        return $this->select(
                        array(
                            'command.id',
                            'user_id',
                            'user_profil.view_name AS user_name',
                            'command.name',
                            'state_id',
                            'created_at',
                            'updated_at',
                            'state_id',
                            'state.designation AS state',
                        ))
                    ->join('user_profil', 'command.user_id=user_profil.id', 'LEFT')
                    ->join('state', 'command.state_id=state.id', 'LEFT')
                    ->like('lower(command.name)', strtolower($key));
                    // ->orLike('lower(name)', strtolower($key))
                    // ->orLike('lower(interlocuter_nom)', strtolower($key));
    }

    public function get_list($key='', $order_by = 'created_at', $sort = 'desc', $nb = 10, $debut = 0)
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

    public function get_view_list($data_list)
    {
        $result = array();

        foreach ($data_list as $key => $data) {
            $payload = array(
                'id' => $data['id'],
                'class' => '',
                'data' => array(
                    //num
                    array(
                        'class' => 'col-2 col-md-1 col-lg-2',
                        'value' => $data['id'],
                        'url' => site_url('card/command/'. $data['id'])
                    ),
                    //object
                    array(
                        'class' => 'col-6 col-md-6 col-sm-4 col-lg-6',
                        'value' => $data['name'],
                        'url' => site_url('card/command/'. $data['id'])
                        
                    ),
                    //state
                    array(
                        'class' => 'd-none d-sm-block col-sm-4 col-lg-2',
                        'value' => lang('STD.std_state_'. $data['state'])
                    ),
                ),
                'menu' => array(
                    array(
                        'value' => lang('STD.std_read_it'),
                        'url' => site_url('card/command/'. $data['id'])
                    )
                )
            );

            array_push($result, $payload);
        }

        return $result;
    }

    public function get_data_structure()
    {
        return array(
            'title' => lang('STD.std_quotation'),
            'data_list' => array(
                array(
                    'type' => 'hidden',
                    'extra' => 'metadata',
                    'name' => 'num',
                    'id' => 'num',
                    'label' => lang('STD.std_num'),
                    'class' => 'metadata-right'
                ),
                array(
                    'type' => 'select',
                    'name' => 'user_client_id',
                    'id' => 'user_client_id',
                    'option_link' => site_url('account/list/client'),
                    'placeholder' => lang('STD.std_client_select'),
                    'label' => lang('STD.std_client'),
                    'url' => site_url('account/{{user_client_id}}'),
                    'view' => 'user_client_name',
                    'can_add_partial' => true,
                    'partial_form_link' => site_url('structure/account/client/1'),
                    'action_link' => site_url('account/client'),
                    'is_required' => true,
                    'class' => 'metadata-right'
                ),
                array(
                    'type' => 'text',
                    'name' => 'adress',
                    'id' => 'adress',
                    'label' => lang('STD.std_adress'),
                    'placeholder' => lang('STD.std_adress'),
                    'class' => 'metadata-right'
                ),
                array(
                    'type' => 'text',
                    'name' => 'object',
                    'id' => 'object',
                    'label' => lang('STD.std_object'),
                    'placeholder' => lang('STD.std_object'),
                    'is_required' => true,
                    'class' => 'mt-30'
                ),
                array(
                    'type' => 'hidden',
                    'extra' => 'table',
                    'metadata' => array(
                        'header' => array('designation', 'qte', 'price_unit_ht', 'price_total'),
                        'title' => lang('STD.std_quotation_line'),
                        'data_list' => array(
                            array(
                                'type' => 'textarea',
                                'name' => 'designation',
                                'id' => 'designation',
                                'label' => lang('STD.std_designation'),
                                'is_required' => true,
                                'is_autosize' => true,
                                'placeholder' => lang('STD.std_designation'),
                            ),
                            array(
                                'type' => 'numeric',
                                'name' => 'qte',
                                'id' => 'qte',
                                'class' => 'text-right',
                                'label' => lang('STD.std_qte'),
                                'is_required' => true,
                                'placeholder' => lang('STD.std_qte'),
                            ),
                            array(
                                'type' => 'numeric',
                                'name' => 'price_unit_ht',
                                'id' => 'price_unit_ht',
                                'class' => 'text-right',
                                'label' => lang('STD.std_unit_price'),
                                'is_required' => true,
                                'placeholder' => lang('STD.std_unit_price'),
                            ),
                            array(
                                'type' => 'text',
                                'name' => 'reduction',
                                'id' => 'reduction',
                                'class' => 'text-right',
                                'label' => lang('STD.std_reduction'),
                                'placeholder' => lang('STD.std_reduction'),
                            ),
                            array(
                                'type' => 'hidden',
                                'extra' => 'evaluation',
                                'name' => 'price_total',
                                'id' => 'price_total',
                                'class' => 'text-right',
                                // price_total - (reduction.includes('%') ? (price_total *  (to_numeric(reduction) / 100) ) : (reduction))
                                'rule' => '({{qte}} * {{price_unit_ht}}) - ("{{reduction}}" != "" ? ( "{{reduction}}".includes("%25") ? (({{qte}} * {{price_unit_ht}}) * parseFloat("{{reduction}}".replace("%25", "")) / 100) : parseFloat("{{reduction}}") ) : 0 )',
                                'label' => lang('STD.std_total_price'),
                                'placeholder' => lang('STD.std_total_price'),
                            ),
                            array(
                                'type' => 'hidden',
                                'extra' => 'button_edit',
                            ),
                            array(
                                'type' => 'hidden',
                                'extra' => 'button_delete',
                            ),
                        )
                    )
                ),
                array(
                    'type' => 'select',
                    'name' => 'payement_condition_id',
                    'id' => 'payement_condition_id',
                    'option_link' => site_url('entity/select/payement_condition'),
                    'placeholder' => lang('STD.std_payement_condition'),
                    'label' => lang('STD.std_payement_condition'),
                    'url' => site_url('entity/payement_condition/{{payement_condition_id}}'),
                    'view' => 'payement_condition',
                    'can_add_partial' => true,
                    'partial_form_link' => site_url('structure/entity?entity=payement_condition'),
                    'action_link' => site_url('entity?entity=payement_condition'),
                    'is_required' => true,
                ),
                array(
                    'type' => 'select',
                    'name' => 'payement_mode_id',
                    'id' => 'payement_mode_id',
                    'option_link' => site_url('entity/select/payement_mode'),
                    'placeholder' => lang('STD.std_payement_mode'),
                    'label' => lang('STD.std_payement_mode'),
                    'url' => site_url('entity/payement_mode/{{payement_mode_id}}'),
                    'view' => 'payement_mode',
                    'can_add_partial' => true,
                    'partial_form_link' => site_url('structure/entity?entity=payement_mode'),
                    'action_link' => site_url('entity?entity=payement_mode'),
                    'is_required' => true,
                ),
                
                
                array(
                    'type' => 'numeric',
                    'name' => 'reduction',
                    'id' => 'reduction',
                    'label' => lang('STD.std_reduction'),
                    'placeholder' => lang('STD.std_reduction_global'),
                ),
                array(
                    'type' => 'hidden',
                    'extra' => 'command',
                    'data_list' => array(
                        array('type' => 'validate', 'condition' => '{{can_validate}}'),
                        array('type' => 'edit', 'condition' => '{{is_editable}}'),
                        array('type' => 'delete', 'condition' => '{{is_deletable}}'),
                        array('type' => 'link', 'condition' => '{{can_print}}', 'label' => lang('STD.std_pdf'), 'icon' => 'fa fa-file-pdf', 'target_blank' => true, 'class' => 'btn btn-primary', 'href' => site_url('document/quotation/{{id}}'))
                    )
                ),

            )
        ); 
    }

    public function get_num_template()
    {
        return 'XXX-' . date('Ymd');
    }

    public function get_next_num($model)
    {
        //Todo : make it command format
        //num format : initial . '-' . date('dmy') . '-' . next_num . '.' . modification_nb 
        if ($model['quotation_id'] == null) {
            $last_file = $this->where(array(
                                    'state_id' => StateModel::$state_validated,
                                    'DAY(date_state_changed)' => Time::now()->getDay(),
                                    "LOCATE('.', num)" => '0'
                                ))
                                ->first();
            $num = '';
            $next_num = 1;

            if ($last_file != null) {
                $last_num = preg_replace('#^'. $this->session->user_identity['initial'] .'-\d{8}-(\d{2})#', '$1', $last_file['num']);
                $last_num = intval($last_num);
                $next_num = $last_num + 1;
            }

            $num = $this->session->user_identity['initial'] . '-' . Time::now()->toLocalizedString('dmy') . '-' . sprintf("%'.02d", $next_num);

            return $num;
        }


        if ($model['quotation_id'] != null) {
            $quotation = $this->find($model['quotation_id']);

            $original_num = $quotation['num'];

            $last_file = $this->where(array(
                                    'state_id' => StateModel::$state_validated,
                                    'user_id' => $this->session->user_identity['id'],
                                    "LOCATE('". $original_num ."', num) >" => 0
                                ))
                                ->orderBy('num', 'desc')
                                ->first();

            if ($last_file != null && strstr($last_file['num'], '.')) {
                $last_num = preg_replace('#^'. $this->session->user_identity['initial'] .'-\d{8}-(\d{2})#', '$1', $last_file['num']);
                $last_num = intval($last_num);
                $next_num = $last_num + 1;
            }

            $num = $original_num . '-' . sprintf("%'.01d", $next_num);
            return $num;
        }
    }

    public function get_data($id)
    {
        //Todo : return quotation or order number associated
        $data = $this->select(
                        array(
                            'quotation.*',
                            'user_profil.view_name AS user_name',
                            'user_profil_client.view_name AS user_client_name',
                            'state_id',
                            'state.designation AS state',
                            'payement_condition.designation AS payement_condition',
                            'payement_mode.designation AS payement_mode'
                        ))
                    ->join('user_profil', 'command.user_id=user_profil.id', 'LEFT')
                    ->join('user_profil AS user_profil_client', 'command.user_client_id=user_profil_client.id', 'LEFT OUTER')
                    ->join('payement_mode', 'command.payement_mode_id=payement_mode.id', 'LEFT')
                    ->join('payement_condition', 'command.payement_condition_id=payement_condition.id', 'LEFT')
                    ->join('state', 'command.state_id=state.id', 'LEFT')
                    ->where('command.id', $id)
                    ->first();

        if ($data == null) {
            return null;
        }

        $data = $this->write_accessibility($data);

        return $data;
    }

    public static function get_role()
    {
        return array(
            'label' => 'std_command',
            'value' => 'command', 
            'data_list' => array(
                array('label' => lang('STD.std_create'), 'value' => 'create'),
                array('label' => lang('STD.std_read'), 'value' => 'read'),
                array('label' => lang('STD.std_update'), 'value' => 'update'),
                array('label' => lang('STD.std_delete'), 'value' => 'delete'),
                array('label' => lang('STD.std_pdf'), 'value' => 'print'),
            )
        );
    }

    public function write_accessibility($data)
    {
        $data['is_editable'] = $this->is_editable($data);
        $data['is_deletable'] = $this->is_deletable($data);
        $data['can_read'] = $this->can_read($data);
        $data['can_reedit'] = $this->can_reedit($data);
        $data['can_validate'] = $this->can_validate($data);
        $data['can_print'] = $this->can_print($data);
        return $data;
    }

    public function can_access_list()
    {
        if ($this->session->is_superadmin()) {
            return true;
        }

        return $this->session->is_authorized('command', 'read', true) || $this->session->is_authorized('command', 'create', true);
    }

    public function can_create()
    {
        if ($this->session->is_superadmin()) {
            return true;
        }

        return $this->session->is_authorized('command', 'create', true);
    }

    public function can_validate($quotation)
    {
        if ($quotation == null) {
            return false;
        }

        if ($quotation['state_id'] == StateModel::$state_validated) {
            return false;
        }

        if ($quotation['user_id'] == $this->session->user_identity['id']) {
            return true;
        }

        //Todo : read quotation only inside group role

        return $this->session->is_authorized('command', 'read', true);
    }

    public function can_print($quotation)
    {
        if ($quotation == null) {
            return false;
        }

        if ($quotation['state_id'] != StateModel::$state_validated) {
            return false;
        }

        if ($quotation['user_id'] == $this->session->user_identity['id']) {
            return true;
        }

        //Todo : read quotation only inside group role

        return $this->session->is_authorized('command', 'print', true);
    }

    public function can_read($quotation)
    {
        if ($quotation == null) {
            return false;
        }

        if ($this->session->is_superadmin()) {
            return true;
        }

        if ($quotation['user_id'] == $this->session->user_identity['id']) {
            return true;
        }

        //Todo : read quotation only inside group role

        return $this->session->is_authorized('command', 'read', true);
    }

    public function is_editable($quotation)
    {
        if ($quotation == null) {
            return false;
        }

        if ($quotation['state_id'] == StateModel::$state_validated) {
            return false;
        }

        if ($this->session->is_superadmin()) {
            return true;
        }

        if ($quotation['user_id'] == $this->session->user_identity['id']) {
            return true;
        }

        return $this->session->is_authorized('command', 'update', true);
    }

    public function is_deletable($quotation)
    {
        if ($quotation == null) {
            return false;
        }

        if ($quotation['state_id'] == StateModel::$state_validated) {
            return false;
        }

        if ($this->session->is_superadmin()) {
            return true;
        }

        if ($quotation['user_id'] == $this->session->user_identity['id']) {
            return true;
        }

        return $this->session->is_authorized('command', 'delete', true);
    }

}