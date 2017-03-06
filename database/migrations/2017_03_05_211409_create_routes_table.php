<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {
        /**
         * Let's verify table doesn't exists
         */
        if (Schema::hasTable("bb_routes")) {
            Schema::table("bb_routes", function(Blueprint $table) {

                if (Schema::hasColumn("bb_routes", "origin")) {
                    $table->string("origin", 125)->change();
                } else {
                    $table->string("origin", 125);
                }

                if (Schema::hasColumn("bb_routes", "destination")) {
                    $table->string("destination", 125)->change();
                } else {
                    $table->string("destination", 125);
                }

                if (Schema::hasColumn("bb_routes", "created_at") && Schema::hasColumn("bb_routes", "updated_at")) {
                    $table->timestamps()->change();
                } else {
                    $table->timestamps();
                }
            });

        /**
         * Let's create table
         */
        } else {
            Schema::create("bb_routes", function(Blueprint $table) {
                $table->increments("id");
                $table->string("origin", 125);
                $table->string("destination", 125);
                $table->timestamps();
            });
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("bb_routes");
    }
}
