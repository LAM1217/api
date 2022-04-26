<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAtributes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idatribute' => ['type' => 'BIGINT','constraint' => 255,'unsigned' => true,'auto_increment' => true],
            'name' => ['type' => 'VARCHAR','constraint' => 255]]);
        $this->forge->addPrimaryKey('idatribute');
        $this->forge->createTable('atributes');
    }

    public function down()
    {
        $this->forge->dropTable('atributes');
    }
}
