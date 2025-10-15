<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusColumn extends Migration
{
    public function up()
    {
        // $fields = [
        //     'status' => [
        //         'type'       => 'ENUM',
        //         'constraint' => ['approved', 'pending','rejected','claimed'],
        //         'default'    => 'pending'
        //     ],
        // ];

        // $this->forge->addColumn('requests', $fields);
    }

    public function down()
    {
        //
    }
}
