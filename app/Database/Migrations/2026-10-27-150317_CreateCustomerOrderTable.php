<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomerOrderTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'customer_name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'item_name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'quantity' => ['type' => 'INT', 'constraint' => 11, 'null' => false],
            'total_price' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false],
            'is_paid' => ['type' => 'TINYINT', 'constraint' => 1, 'null' => true],
            'ordered_at' => ['type' => 'DATETIME', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('customer_orders');
    }

    public function down()
    {
        $this->forge->dropTable('customer_orders');
    }
}
