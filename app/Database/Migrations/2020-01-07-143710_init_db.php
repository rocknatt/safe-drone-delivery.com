<?php 

namespace App\Database\Migrations;

class Init_db extends \CodeIgniter\Database\Migration {

    public function up()
    {
        /**
         * user_role
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true
            ),
            'designation' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'rules' => array(
                'type' => 'TEXT',
            ),
            'comments' => array(
                'type' => 'TEXT',
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->createTable('user_role', false, array('ENGINE' => 'InnoDB'));

        /**
         * user
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true
            ),
            'user_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'unique' => true,
            ),
            'user_name_tolower' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'unique' => true,
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'email_tolower' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'uniq_id_reset_pass' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'date_ajout_uniq_id_reset_pass' => array(
                'type' => 'DATETIME',
                'null' => true,
                'default' => NULL,
            ),
            'date_dernier_modification_password' => array(
                'type' => 'DATETIME',
                'null' => true,
                'default' => NULL,
            ),
            'date_inscription' => array(
                'type' => 'DATETIME',
                'null' => true,
                'default' => NULL,
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
            ),
            'is_blocked' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => '0',
            ),
            'added_by_user_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ),
            'user_role_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'default' => '1',
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->addKey('added_by_user_id');
        $this->forge->addKey('user_role_id');
        $this->forge->addForeignKey('user_role_id', 'user_role', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('user', false, array('ENGINE' => 'InnoDB'));

        /**
         * gallery
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => true,
                'default' => NULL,
            ),
            'comments' => array(
                'type' => 'TEXT',
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('gallery', false, array('ENGINE' => 'InnoDB'));

        /**
         * image
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true
            ),
            'file_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'gallery_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => true,
                'default' => NULL,
            ),
            'comments' => array(
                'type' => 'TEXT',
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->addKey('gallery_id');
        $this->forge->addForeignKey('gallery_id', 'gallery', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('image', false, array('ENGINE' => 'InnoDB'));

        /**
         * user_category
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true
            ),
            'designation' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'comments' => array(
                'type' => 'TEXT',
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->createTable('user_category', false, array('ENGINE' => 'InnoDB'));

        /**
         * user_profil
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'first_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '125',
            ),
            'view_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'adress' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'telephone' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '75',
            ),
            'site_web' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'initial' => array(
                'type' => 'VARCHAR',
                'constraint' => '5',
            ),
            'cin' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'nif' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'stat' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'rcs' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'user_category_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ),
            'image_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_category_id');
        $this->forge->addKey('image_id');
        $this->forge->addForeignKey('id', 'user', 'id');
        $this->forge->addForeignKey('user_category_id', 'user_category', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('image_id', 'image', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('user_profil', false, array('ENGINE' => 'InnoDB'));

        /**
         * user_activity
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true
            ),
            'designation' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'comments' => array(
                'type' => 'TEXT',
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->createTable('user_activity', false, array('ENGINE' => 'InnoDB'));

        /**
         * user_param
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true
            ),
            'user_activity_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_activity_id');
        $this->forge->addForeignKey('user_activity_id', 'user_activity', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('user_param', false, array('ENGINE' => 'InnoDB'));

        /**
         * session
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true
            ),
            'token' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'created_at' => array(
                'type' => 'DATETIME',
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'user_agent' => array(
                'type' => 'VARCHAR',
                'constraint' => '250',
            ),
            'browser' => array(
                'type' => 'VARCHAR',
                'constraint' => '75',
            ),
            'browser_version' => array(
                'type' => 'VARCHAR',
                'constraint' => '20'
            ),
            'mobile_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            ),
            'robot_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            ),
            'referer' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'plateform' => array(
                'type' => 'VARCHAR',
                'constraint' => '125',
            )
        ));
        $this->forge->addKey('id', true);
        $this->forge->createTable('session', false, array('ENGINE' => 'InnoDB'));

        /**
         * session_user
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true
            ),
            'session_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ),
            'is_remember' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 0
            ),
            'is_authenticated' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 0
            ),
            'is_active' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 0
            ),
            'is_on_activity' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => 0
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->addKey('session_id');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('session_id', 'session', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('session_user', false, array('ENGINE' => 'InnoDB'));

        /**
         * session_user_date_activity
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
            ),
            'session_user_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ),
            'date_begin' => array(
                'type' => 'DATETIME',
            ),
            'date_end' => array(
                'type' => 'DATETIME',
                'null' => true,
                'default' => NULL,
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->addKey('session_user_id');
        $this->forge->addForeignKey('session_user_id', 'session_user', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('session_user_date_activity', false, array('ENGINE' => 'InnoDB'));

        /**
         * session_user_ip
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
            ),
            'session_user_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
            ),
            'ip_adress' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->addKey('session_user_id');
        $this->forge->addForeignKey('session_user_id', 'session_user', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('session_user_ip', false, array('ENGINE' => 'InnoDB'));

        /**
         * Default seed
         */
        $seeder = \Config\Database::seeder();
        $seeder->call('InitSeeder');
    }

    public function down()
    {
        $this->forge->dropTable('image', true);
        $this->forge->dropTable('gallery', true);

        $this->forge->dropTable('user_profil', true);
        $this->forge->dropTable('user', true);
        $this->forge->dropTable('user_role', true);
        $this->forge->dropTable('user_category', true);

        $this->forge->dropTable('user_param', true);
        $this->forge->dropTable('user_activity', true);

        $this->forge->dropTable('session_user_ip', true);
        $this->forge->dropTable('session_user_date_activity', true);
        $this->forge->dropTable('session_user', true);
        $this->forge->dropTable('session', true);

    }
}