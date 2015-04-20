<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Rentals extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'category' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
			'description' => array(
				'type' => 'TEXT',
			),
			'numBedrooms' => array(
				'type' => 'INT',
			),
			'startingPrice' => array(
				'type' => 'DOUBLE',
			),
			'thumbnail' => array(
				'type' => 'VARCHAR',
				'constraint' => '500',
			),
			'images' => array(
				'type' => 'VARCHAR',
				'constraint' => '500',
			),
			'amenities' => array(
				'type' => 'TEXT',
			),
			'bedrooms' => array(
				'type' => 'TEXT',
			),
			'services' => array(
				'type' => 'TEXT',
			),
			'rates' => array(
				'type' => 'TEXT',
			),
			'img_path' => array(
				'type' => 'VARCHAR',
				'constraint' => '200'
			)
		));

		// define id as primary key
		$this->dbforge->add_key('id', TRUE);

		$this->dbforge->create_table('rentals');
	}

	public function down()
	{
		$this->dbforge->drop_table('rentals');
	}
}