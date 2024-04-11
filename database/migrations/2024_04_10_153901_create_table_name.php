<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('prepare', function (Blueprint $table) {
            // 1. 修改 preid 欄位
            // $table->bigIncrements('preid')->change(); // bigint(20), UNSIGNED, AUTO_INCREMENT, PRIMARY KEY

            // 2. 修改 tlid 欄位
            // $table->bigInteger('tlid')->unsigned()->index()->change(); // bigint(20), UNSIGNED, INDEX

            // 3. 修改 pretitle 欄位
            // $table->string('pretitle', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable(false)->change(); // varchar(255), utf8mb4_unicode_ci, NOT NULL

            // 4. 修改 pretext 欄位
            $table->string('pretext', 1000)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable()->change(); // varchar(1000), utf8mb4_unicode_ci, NULL

            // 5. 修改 type 欄位
            $table->string('type', 2)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable()->change(); // varchar(2), utf8mb4_unicode_ci, NULL

            // 6. 修改 checked 欄位
            // $table->tinyInteger('checked')->default(0)->nullable(false)->change(); // tinyint(1), NOT NULL, DEFAULT 0
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // 如果需要撤銷更改，可以在這裡編寫撤銷邏輯
    }
};
