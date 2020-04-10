<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Product extends Migration
{
	public function up()
	{
		//

		/**
         * product_category
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true
            ),
            'tag_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'designation' => array(
                'type' => 'VARCHAR',
                'constraint' => '125',
            ),
            'comments' => array(
                'type' => 'TEXT',
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->createTable('product_category', false, array('ENGINE' => 'InnoDB'));

        /**
         * product
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true
            ),
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
            'product_category_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
                'default' => null,
            ),
            'gallery_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ),
            'tag_link' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'price_before' => array(
                'type' => 'DECIMAL',
                'constraint' => '13,2',
            ),
            'price' => array(
                'type' => 'DECIMAL',
                'constraint' => '13,2',
            ),
            'description' => array(
                'type' => 'TEXT',
            ),
            'is_visible' => array(
                'type' => 'TINYINT',
                'default' => 0
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('product_category_id');
        $this->forge->addKey('gallery_id');
        $this->forge->addForeignKey('user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('product_category_id', 'product_category', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('gallery_id', 'gallery', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('product', false, array('ENGINE' => 'InnoDB'));

        /**
         * state
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
        $this->forge->createTable('state', false, array('ENGINE' => 'InnoDB'));


        /**
         * command
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true
            ),
            'created_at' => array(
                'type' => 'DATETIME',
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'phone_number' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'adress' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'longitude' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'latitude' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'altitude' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'state_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('state_id');
        $this->forge->addForeignKey('user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('state_id', 'state', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('command', false, array('ENGINE' => 'InnoDB'));

        /**
         * command_line
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true
            ),
            'command_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ),
            'product_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '125',
            ),
            'qte' => array(
                'type' => 'INT',
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->addKey('command_id');
        $this->forge->addForeignKey('command_id', 'command', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('command_line', false, array('ENGINE' => 'InnoDB'));

        /**
         * drone
         */
        $this->forge->addField(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true
            ),
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
            'image_id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
                'default' => null,
            ),
            'ref' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'longitude' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'latitude' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'altitude' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
        ));
        $this->forge->addKey('id', true);
        $this->forge->addKey('image_id');
        $this->forge->addForeignKey('image_id', 'image', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('drone', false, array('ENGINE' => 'InnoDB'));

        /**
         * Default seed
         */
        $seeder = \Config\Database::seeder();
        $seeder->call('StateSeeder');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		//

		$this->forge->dropTable('product', true);
        $this->forge->dropTable('product_category', true);
        $this->forge->dropTable('state', true);
        $this->forge->dropTable('command_line', true);
        $this->forge->dropTable('command', true);
        $this->forge->dropTable('drone', true);
	}
}
