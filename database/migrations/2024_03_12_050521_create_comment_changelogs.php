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
        Schema::create("comment_changelogs", function (Blueprint $table) {
            $table->id();
            $table->string("cid")->comment("Comment ID. 'id' in the comment table.");
            $table->string("before")->comment("Comment before changing.");
            $table->string("after")->comment("Comment after changing.");
            $table->timestamps();
        });
        Schema::table("comments", function (Blueprint $table) {
            DB::unprepared('CREATE TRIGGER log_comment_changing AFTER UPDATE ON `comments` FOR EACH ROW
            BEGIN
                INSERT INTO comment_changelogs(`cid`, `before`, `after`, `created_at`) VALUES(OLD.cid, OLD.comment, NEW.comment, NOW());
            END');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_changelog');
        DB::unprepared('DROP TRIGGER `log_comment_changing`');
    }
};
