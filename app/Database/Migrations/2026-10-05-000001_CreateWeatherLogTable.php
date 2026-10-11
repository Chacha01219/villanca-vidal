<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWeatherLogTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'temperature' => [
                'type'       => 'DECIMAL',
                'constraint' => [5, 2],
            ],
            'fetched_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('weather_log');
    }

    public function down()
    {
        $this->forge->dropTable('weather_log');
    }
}
