<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Sessions extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'session_id' => array(
				'type' => 'BINARY',
				'constraint' => '64',
				'null' => FALSE
			),
			'user_id' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'null' => FALSE
			),
			'csrf_id' => array(
				'type' => 'BINARY',
				'constraint' => '64'
			),
			'last_activity' => array(
				'type' => 'TIMESTAMP',
			),
			'first_activity' => array(
				'type' => 'TIMESTAMP',
			),
		));

		// define id as primary key
		$this->dbforge->add_key('session_id', TRUE);

		$this->dbforge->create_table('sessions');

	}

	public function down()
	{
		$this->dbforge->drop_table('sessions');
	}

}