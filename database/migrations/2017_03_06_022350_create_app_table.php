<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable("aa_app")) {

            Schema::table("aa_app", function(Blueprint $table) {
                if (!Schema::hasColumn("aa_app", "id")) {
                    $table->increments("id");
                }
                if (!Schema::hasColumn("aa_app", "state")) {
                    $table->boolean("state");
                }
                if (!Schema::hasColumn("aa_app", "max_clients")) {
                    $table->integer("max_clients");
                }
                if (!Schema::hasColumn("aa_app", "max_users")) {
                    $table->integer("max_users");
                }
                if (!Schema::hasColumn("aa_app", "created_at")) {
                    $table->timestamp("created_at");
                }
                if (!Schema::hasColumn("aa_app", "updated_at")) {
                    $table->timestamp("updated_at");
                }
            });

        } else {
            Schema::create("aa_app", function(Blueprint $table) {
                $table->increments("id");
                $table->boolean("state");
                $table->integer("max_clients");
                $table->integer("max_users");
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
        Schema::drop("aa_app");
    }
}
