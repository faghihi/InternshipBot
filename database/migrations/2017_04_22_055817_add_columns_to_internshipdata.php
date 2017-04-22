<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToInternshipdata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('internshipdata', function($table) {
            $table->text('major')->default('not set');
            $table->text('time')->default('not set');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('internshipdata', function($table) {
            $table->dropColumn('major');
            $table->dropColumn('time');

        });
    }
}
