<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutePointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable("bb_points")) {
            Schema::table("bb_points", function(Blueprint $table) {
                if (!Schema::hasColumn("bb_points", "route_id")) {
                    $table->foreign("route_id")->reference("id")->on("bb_routes");
                }
                if (!Schema::hasColumn("bb_points", "lat")) {
                    $table->float("lat", 15, 8);
                }
                if (!Schema::hasColumn("bb_points", "lng")) {
                    $table->float("lng", 15, 8);
                }
                if (!Schema::hasColumn("bb_points", "ordinal")) {
                    $table->integer("ordinal");
                }
                if (!Schema::hasColumn("bb_points", "created_at") && Schema::hasColumn("bb_points", "updated_at")) {
                    $table->timestamps();
                }
            });

        } else {
            Schema::create("bb_points", function(Blueprint $table) {
                $table->increments("id");
                $table->foreign("route_id")->references("id")->on("bb_routes");
                $table->float("lat", 15, 8);
                $table->float("lng", 15, 8);
                $table->integer("ordinal");
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
        Schema::drop("bb_points");
    }
}
