<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable("aa_entities")) {
            Schema::table("aa_entities", function(Blueprint $table) {
                if (!Schema::hasColumn("aa_entities", "id")) {
                    $table->increments("id");
                }
                if (!Schema::hasColumn("aa_entities", "name")) {
                    $table->string("name", 125);
                }
                if (!Schema::hasColumn("aa_entities", "lat")) {
                    $table->float("lat", 15, 8);
                }
                if (!Schema::hasColumn("aa_entities", "lng")) {
                    $table->float("lng", 15, 8);
                }
                if (!Schema::hasColumn("aa_entities", "entity_type")) {
                    $table->smallInteger("entity_type");
                }
                if (!Schema::hasColumn("aa_entities", "route_id")) {
                    $table->foreign("route_id")->references("id")->on("bb_routes")->nullable();
                }
                if (!Schema::hasColumn("aa_entities", "last_point_id")) {
                    $table->foreign("last_point_id")->references("id")->on("bb_points")->nullable();
                }
                if (!Schema::hasColumn("aa_entities", "created_at")) {
                    $table->timestamp("created_at");
                }
                if (!Schema::hasColumn("aa_entities", "updated_at")) {
                    $table->timestamp("updated_at");
                }
            });
        } else {
            Schema::create("aa_entities", function(Blueprint $table) {
                $table->increments("id");
                $table->string("name", 125);
                $table->float("lat", 15, 8);
                $table->float("lng", 15, 8);
                $table->smallInteger("entity_type");
                $table->foreign("route_id")->references("id")->on("bb_routes")->nullable();
                $table->foreign("last_point_id")->references("id")->on("bb_points")->nullable();
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
        Schema::drop("aa_entities");
    }
}
