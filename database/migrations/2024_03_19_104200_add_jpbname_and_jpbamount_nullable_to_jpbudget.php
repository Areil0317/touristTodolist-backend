<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jpbudget', function (Blueprint $table) {
            $table->string('jpbname')->nullable()->change();
            $table->string('jpbamount', 11)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jpbudget', function (Blueprint $table) {
            $table->string('jpbname')->nullable(false)->change();
            $table->string('jpbamount', 11)->nullable(false)->change();
        });
    }
};
