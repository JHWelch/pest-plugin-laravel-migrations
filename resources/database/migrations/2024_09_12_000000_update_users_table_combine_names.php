<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableCombineNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add a new column for the combined name
            $table->string('full_name')->nullable();
        });

        // Update the existing records with the combined name
        DB::table('users')->update([
            'full_name' => DB::raw("first_name || ' ' || last_name"),
        ]);

        // Remove the first_name and last_name columns
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add the first_name and last_name columns back
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
        });

        // Update the existing records with the original names
        DB::table('users')->update([
            'first_name' => DB::raw("SUBSTR(full_name, 1, INSTR(full_name, ' ') - 1)"),
            'last_name' => DB::raw("SUBSTR(full_name, INSTR(full_name, ' ') + 1)"),
        ]);

        // Remove the full_name column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('full_name');
        });
    }
}
