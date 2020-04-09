<?php 

namespace App\Database\Seeds;

class StateSeeder extends \CodeIgniter\Database\Seeder
{

    public function run()
    {
        /**
         * state
         */
        $data_list = array(
            array('designation' => 'created', 'comments' => ''),
            array('designation' => 'process', 'comments' => ''),
            array('designation' => 'validated', 'comments' => ''),
            array('designation' => 'finished', 'comments' => ''),
            array('designation' => 'folded', 'comments' => ''),
        );

        foreach ($data_list as $data) {
            $this->db->table('state')->insert($data);
        }
    }
}