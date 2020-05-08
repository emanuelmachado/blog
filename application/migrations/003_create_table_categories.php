<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Table_Categories extends CI_Migration {
    
    private $table;

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();

        $this->table = "categories";
	}

	public function up() {
		$this->dbforge->add_field([
			'id' => [
				'type'           => 'INT',
				'constraint'     => '11',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			],
			'name' => [
				'type'       => 'VARCHAR',
				'constraint' => '50',
			],
			'status' => [
				'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => true
			]
        ]);
        
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table($this->table);
	}

	public function down() {
		$this->dbforge->drop_table($this->table, TRUE);
	}
}
