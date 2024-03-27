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
        Schema::table('journey', function (Blueprint $table) {
            $table->dropForeign(["tlid"]);
            $table->foreign("tlid")->references('tlid')->on('touristlist')->onDelete('cascade');
        });
    
        Schema::table('journeyproject', function (Blueprint $table) {
            $table->dropForeign(["jid"]);
            $table->foreign("jid")->references('jid')->on('journey')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journey', function (Blueprint $table) {
            $table->dropForeign(["tlid"]);
            $table->foreign("tlid")->references('tlid')->on('touristlist')->onDelete('cascade');
        });
    
        Schema::table('journeyproject', function (Blueprint $table) {
            $table->dropForeign(["jid"]);
            $table->foreign("jid")->references('jid')->on('journey')->onDelete('cascade');
        });
    }
};
