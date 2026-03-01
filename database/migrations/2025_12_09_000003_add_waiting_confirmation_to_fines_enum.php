<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Modify the enum to include 'waiting_confirmation'
        DB::statement("ALTER TABLE fines MODIFY COLUMN `status` ENUM('pending','waiting_confirmation','paid') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE fines MODIFY COLUMN `status` ENUM('pending','paid') NOT NULL DEFAULT 'pending'");
    }
};
