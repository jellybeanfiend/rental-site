<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_New_registrations extends CI_Migration {

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
			'hash' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
			'date_added' => array(
				'type' => 'TIMESTAMP',
			),
		));

		// define id as primary key
		$this->dbforge->add_key('id', TRUE);

		$this->dbforge->create_table('new_registrations');
	}

	public function down()
	{
		$this->dbforge->drop_table('new_registrations');
	}

}