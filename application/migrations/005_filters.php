<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Filters extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'text' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
			'type' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
		));

		// define id as primary key
		$this->dbforge->add_key('id', TRUE);

		$this->dbforge->create_table('filters');
	}

	public function down()
	{
		$this->dbforge->drop_table('filters');
	}
}