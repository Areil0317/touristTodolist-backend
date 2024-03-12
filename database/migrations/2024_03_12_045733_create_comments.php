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
        Schema::create("comments", function (Blueprint $table) {
            $table->uuid("id")->primary();
            // ID relationships
            $table->bigInteger("uid")->comment("User ID. id in users table.");
            $table->bigInteger("tid")->comment("Item ID. id in items table. The 't' here means 'thread'.");
            // Rating comments
            $table->string("comment")->default("")->comment("Comment for the item");
            $table->bigInteger("rate")->default(0)->comment("Rating for the item. Usually it should be 0~10.");
            // Dates
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
