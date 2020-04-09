<?php 

namespace App\Database\Seeds;

class InitSeeder extends \CodeIgniter\Database\Seeder
{

    public function run()
    {
        /**
         * gallery
         */
        $data_list = array(
            array('comments' => 'mailing'),
            array('comments' => 'image_profil'),
            array('comments' => 'image_cover'),
        );

        foreach ($data_list as $data) {
            $this->db->table('gallery')->insert($data);
        }

        /**
         * user_role
         */
        $user_role_admin = array(
            'user' => array('index' => true, 'create' => true, 'block' => true)
        );
        $data_list = array(
            array('designation' => 'client'),
            array('designation' => 'super_admin'),
            array('designation' => 'admin', 'rules' => json_encode($user_role_admin)),
            array('designation' => 'agent'),
        );

        foreach ($data_list as $data) {
            $this->db->table('user_role')->insert($data);
        }

        /**
         * user
         * insert superadmin by default
         */
        $data = array( 
            'user_name' => 'superadmin',
            'user_name_tolower' => 'superadmin',
            'email' => 'superadmin',
            'email_tolower' => 'superadmin',
            'password' => md5('user'),
            'date_inscription' => date('Y-m-d H:i:s'),
            'user_role_id' => 2,
        );
        $this->db->table('user')->insert($data);

        /**
         * user_profil
         */
        $data = array( 
            'name' => 'superadmin',
            'view_name' => 'superadmin',
            'id' => 1
        );
        $this->db->table('user_profil')->insert($data);

        /**
         * user_activity
         */
        $data_list = array(
            array('designation' => 'active'),
            array('designation' => 'do_not_disturb'),
            array('designation' => 'invisible'),
        );

        foreach ($data_list as $data) {
            $this->db->table('user_activity')->insert($data);
        }

        /**
         * user_category
         */
        $data_list = array(
            array('designation' => 'client'),
            array('designation' => 'supplier'),
        );

        foreach ($data_list as $data) {
            $this->db->table('user_category')->insert($data);
        }
    }
}