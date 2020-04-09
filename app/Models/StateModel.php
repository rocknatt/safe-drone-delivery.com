<?php 

namespace App\Models;

use CodeIgniter\Model;

class StateModel extends Model
{
    protected $table      = 'state';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    public static $state_created = '1';
    public static $state_process = '2';
    public static $state_validated = '3';
    public static $state_finished = '4';
    public static $state_folded = '5';

}