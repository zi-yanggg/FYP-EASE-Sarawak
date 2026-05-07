<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMessageTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'msg_id'        => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'email'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'phone'         => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'subject'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'msg'           => ['type' => 'TEXT'],
            'status'        => ['type' => 'ENUM', 'constraint' => ['new', 'read'], 'default' => 'new'],
            'is_deleted'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_date'  => ['type' => 'DATETIME', 'null' => false],
            'modified_date' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('msg_id');
        $this->forge->createTable('message');
    }

    public function down()
    {
        $this->forge->dropTable('message');
    }
}
