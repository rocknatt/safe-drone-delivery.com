<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProfilData extends Migration
{
	public function up()
	{
		//
		/**
		 * user_profil make field nullable
		 */
		$fields = array(
            'adress' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'default' => null
            ),
            'telephone' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'default' => null
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '75',
                'null' => true,
                'default' => null
            ),
            'site_web' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'default' => null
            ),
            'initial' => array(
                'type' => 'VARCHAR',
                'constraint' => '5',
                'null' => true,
                'default' => null
            ),
            'cin' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'default' => null
            ),
            'nif' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'default' => null
            ),
            'stat' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'default' => null
            ),
            'rcs' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'default' => null
            ),
        );
        $this->forge->addField($fields);
        $this->forge->modifyColumn('user_profil', $fields);

        /**
         * user_follow
         */
        $this->forge->addField(array(
            'created_at' => array(
                'type' => 'DATETIME',
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'deleted_at' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ),
            'user_followed_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ),
        ));
        $this->forge->addKey('user_id');
        $this->forge->addKey('user_followed_id');
        $this->forge->addForeignKey('user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_followed_id', 'user', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('user_follow', false, array('ENGINE' => 'InnoDB'));

        /**
         * user_friend
         */
        $this->forge->addField(array(
            'created_at' => array(
                'type' => 'DATETIME',
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'deleted_at' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ),
            'user_friend_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ),
            'date_blocked' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'is_blocked' => array(
                'type' => 'TINYINT',
                'default' => 0
            ),
        ));
        $this->forge->addKey('user_id');
        $this->forge->addKey('user_friend_id');
        $this->forge->addForeignKey('user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_friend_id', 'user', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('user_friend', false, array('ENGINE' => 'InnoDB'));

        /**
         * user_notification
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'date_read' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ),
            'message' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'link' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'object_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'object_type' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'object_occurence' => array(
                'type' => 'SMALLINT',
                'constraint' => 5,
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('user_notification', false, array('ENGINE' => 'InnoDB'));

        /**
         * user_notification_param
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true
            ),
            'unread_nb' => array(
                'type' => 'SMALLINT',
                'constraint' => 5,
                'unsigned' => true,
                'default' => 0
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ),
            'param' => array(
                'type' => 'TEXT',
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('user_notification_param', false, array('ENGINE' => 'InnoDB'));
	}

	//--------------------------------------------------------------------

	public function down()
	{
		//
		/**
		 * user_profil
		 */
		$fields = array(
            'adress' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ),
            'telephone' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '75',
                'null' => false,
            ),
            'site_web' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ),
            'initial' => array(
                'type' => 'VARCHAR',
                'constraint' => '5',
                'null' => false,
            ),
            'cin' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ),
            'nif' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ),
            'stat' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ),
            'rcs' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ),
        );
        $this->forge->addField($fields);
        $this->forge->modifyColumn('user_profil', $fields);
        $this->forge->dropTable('user_notification_param', true);
        $this->forge->dropTable('user_notification', true);
        $this->forge->dropTable('user_friend', true);
        $this->forge->dropTable('user_follow', true);
	}
}
