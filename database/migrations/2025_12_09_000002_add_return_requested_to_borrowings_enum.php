<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Modify the enum to include 'return_requested'
        // Using raw statement for portability with MySQL
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN `status` ENUM('active','returned','overdue','return_requested') NOT NULL DEFAULT 'active'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN `status` ENUM('active','returned','overdue') NOT NULL DEFAULT 'active'");
    }
};
