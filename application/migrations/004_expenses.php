<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Expenses extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'user_id' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
			),
			'date' => array(
				'type' => 'DATE',
			),
			'description' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
			'tags' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
			'amt_mxn' => array(
				'type' => 'DOUBLE',
			),
			'amt_usd' => array(
				'type' => 'DOUBLE',
			),
			'category' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
			'receipt_image' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
			'deleted' => array(
				'type' => 'BOOLEAN',
				'default' => 0
			)
		));

		// define id as primary key
		$this->dbforge->add_key('id', TRUE);

		$this->dbforge->create_table('expenses');
	}

	public function down()
	{
		$this->dbforge->drop_table('expenses');
	}
}