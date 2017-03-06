<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntitiesActiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable("cc_active")) {
            Schema::table("cc_active", function(Blueprint $table) {
                if (!Schema::hasColumn("cc_active", "entity_id")) {
                    $table->foreign("entity_id")->references("id")->on("aa_entities");
                } else {
                    $table->foreign("entity_id")->references("id")->on("aa_entities")->change();
                }
            });
        } else {
            Schema::create("cc_active", function(Blueprint $table) {
                $table->foreign("entity_id")->references("id")->on("aa_entities");
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
        Schema::drop("cc_active");
    }
}
