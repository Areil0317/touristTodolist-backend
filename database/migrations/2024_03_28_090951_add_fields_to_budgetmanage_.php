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
        Schema::create('budgetmanage', function (Blueprint $table) {
            $table->id('bmid');
            $table->foreignId('tlid')->references('tlid')->on('touristlist')->onDelete('cascade');
            $table->string('bmname')->nullable();
            $table->string('bmamount', 11)->nullable();
            $table->string('bmchecked', 2);
        });

        Schema::create('partner', function (Blueprint $table) {
            $table->id('pnid');
            $table->foreignId('bmid')->references('bmid')->on('budgetmanage')->onDelete('cascade');
            $table->string('pnname')->nullable();
            $table->string('pnamount', 11)->nullable();
            $table->string('pnchecked', 2);
        });

        Schema::table('touristlist', function (Blueprint $table) {
            $table->string('totalamount', 11)->nullable();
            $table->string('tlphoto', 200)->default('');
        });

        Schema::table('jimage', function (Blueprint $table) {
            $table->dropForeign(["jid"]);
            $table->foreign("jid")->references('jid')->on('journey')->onDelete('cascade');
        });
        Schema::table('jbudget', function (Blueprint $table) {
            $table->dropForeign(["jid"]);
            $table->foreign("jid")->references('jid')->on('journey')->onDelete('cascade');
        });

        Schema::table('jpimage', function (Blueprint $table) {
            $table->dropForeign(["jpid"]);
            $table->foreign("jpid")->references('jpid')->on('journeyproject')->onDelete('cascade');
        });
        Schema::table('jpbudget', function (Blueprint $table) {
            $table->dropForeign(["jpid"]);
            $table->foreign("jpid")->references('jpid')->on('journeyproject')->onDelete('cascade');
        });

        Schema::table('project', function (Blueprint $table) {
            $table->dropForeign(["aid"]);
            $table->foreign("aid")->references('aid')->on('attractions')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('budgetmanage', function (Blueprint $table) {
            $table->id('bmid');
            $table->foreignId('tlid')->references('tlid')->on('touristlist')->onDelete('cascade');
            $table->string('bmname')->nullable();
            $table->string('bmamount', 11)->nullable();
            $table->string('bmchecked', 2);
        });

        Schema::create('partner', function (Blueprint $table) {
            $table->id('pnid');
            $table->foreignId('bmid')->references('bmid')->on('budgetmanage')->onDelete('cascade');
            $table->string('pnname')->nullable();
            $table->string('pnamount', 11)->nullable();
            $table->string('pnchecked', 2);
        });

        Schema::table('touristlist', function (Blueprint $table) {
            $table->string('totalamount', 11)->nullable();
            $table->string('tlphoto', 200)->default('');
        });

        Schema::table('jimage', function (Blueprint $table) {
            $table->dropForeign(["jid"]);
            $table->foreign("jid")->references('jid')->on('journey')->onDelete('cascade');
        });
        Schema::table('jbudget', function (Blueprint $table) {
            $table->dropForeign(["jid"]);
            $table->foreign("jid")->references('jid')->on('journey')->onDelete('cascade');
        });

        Schema::table('jpimage', function (Blueprint $table) {
            $table->dropForeign(["jpid"]);
            $table->foreign("jpid")->references('jpid')->on('journeyproject')->onDelete('cascade');
        });
        Schema::table('jpbudget', function (Blueprint $table) {
            $table->dropForeign(["jpid"]);
            $table->foreign("jpid")->references('jpid')->on('journeyproject')->onDelete('cascade');
        });

        Schema::table('project', function (Blueprint $table) {
            $table->dropForeign(["aid"]);
            $table->foreign("aid")->references('aid')->on('attractions')->onDelete('cascade');
        });
    }
};
