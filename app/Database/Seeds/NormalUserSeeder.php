<?php 

namespace App\Database\Seeds;

class NormalUserSeeder extends \CodeIgniter\Database\Seeder
{

    public function run()
    {
        /**
         * user
         * insert user client default
         */
        $data_list = array(
            array('user_name' => 'rocknatt', 'user_name_tolower' => 'rocknatt', 'email' => 'fahernatt@gmail.com', 'email_tolower' => 'fahernatt@gmail.com', 'password' => md5('user'), 'date_inscription' => date('Y-m-d H:i:s')),
            array('user_name' => 'ryan', 'user_name_tolower' => 'ryan', 'email' => 'ryan@gmail.com', 'email_tolower' => 'ryan@gmail.com', 'password' => md5('user'), 'date_inscription' => date('Y-m-d H:i:s')),
            array('user_name' => 'laura', 'user_name_tolower' => 'laura', 'email' => 'laura@gmail.com', 'email_tolower' => 'laura@gmail.com', 'password' => md5('user'), 'date_inscription' => date('Y-m-d H:i:s')),
            array('user_name' => 'ethan', 'user_name_tolower' => 'ethan', 'email' => 'ethan@gmail.com', 'email_tolower' => 'ethan@gmail.com', 'password' => md5('user'), 'date_inscription' => date('Y-m-d H:i:s')),
            array('user_name' => 'mia', 'user_name_tolower' => 'mia', 'email' => 'mia@gmail.com', 'email_tolower' => 'mia@gmail.com', 'password' => md5('user'), 'date_inscription' => date('Y-m-d H:i:s')),
        );

        foreach ($data_list as $data) {
            $this->db->table('user')->insert($data);
        }
        
        /**
         * user_profil
         */
        
        $data_list = array(
            array('name' => 'Nantenaina', 'first_name' => 'Fahendrena', 'view_name' => 'Nantenaina Fahendrena'),
            array('name' => 'Ryan', 'first_name' => 'Vins', 'view_name' => 'Vins Ryan'),
            array('name' => 'Laura', 'first_name' => 'Brem', 'view_name' => 'Laura Brem'),
            array('name' => 'Ethan', 'first_name' => 'Hunt', 'view_name' => 'Ethan Hunt'),
            array('name' => 'Mia', 'first_name' => 'Towsen', 'view_name' => 'Mia Towsen'),
        );

        foreach ($data_list as $data) {
            $this->db->table('user_profil')->insert($data);
        }

    }
}