<?php 

namespace App\Models\Command;

use CodeIgniter\Model;

class CommandLineModel extends Model
{
    protected $table      = 'command_line';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = array(
        'user_id',
        'command_id',
        'product_id',
        'qte',
    );

    protected $useTimestamps = true;

    public function get_list($command_id)
    {
        return $this->where('command_id', $command_id)
                    ->orderBy('sort_index', 'asc')
                    ->findAll();
    }

}