<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMenu extends Migration
{
  public function up()
    {
        $this->forge->addField([
            'idmenu' => ['type' => 'BIGINT','constraint' => 255,'unsigned' => true,'auto_increment' => true],
            'name' => ['type' => 'VARCHAR','constraint' => 255],
            'descripcion' => ['type' => 'VARCHAR','constraint' => 255],
            'description_for_plate' => ['type' => 'VARCHAR','constraint' => 255],
            'iduser' => ['type' => 'BIGINT','constraint' => 255],
            'icon' => ['type' => 'VARCHAR','constraint' => 255],
            'active' => ['type' => 'VARCHAR','constraint' => 255]]);
        $this->forge->addPrimaryKey('idmenu');
        $this->forge->createTable('menu');
    }

    public function down()
    {
        $this->forge->dropTable('menu');
    }
}
