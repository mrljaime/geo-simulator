<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 15/10/16
 * Time: 11:13 AM
 */

namespace App\Http\Controllers;

use App\Models\Entities;
use App\Routes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class EntitiesController extends Controller
{
    public function index()
    {
        return view("entities.index");
    }

    public function entities()
    {
        /*******************************
         * WARNING:
         *      This query is build in that way to avoid this:
         *      $entities = Entities::all();
         *      Because when we do a "$entities->route->something" we're doing lazy loading.
         *      So, in that way we're making an unnecessary query every time that get the route
         *******************************/

        $entities = DB::table("aa_entities")
            ->leftJoin("bb_routes", "aa_entities.route_id", "=", "bb_routes.id")
            ->select("aa_entities.id", "aa_entities.name", "aa_entities.entity_type",
                "bb_routes.id as route_id", "bb_routes.origin", "bb_routes.destination")
            ->orderBy("aa_entities.entity_type", "asc")
            ->get();

        $values = [];

        foreach ($entities as $entity) {

            if (is_null($entity->origin)) {
                $values[] = array(
                    "id" => $entity->id,
                    "name" => $entity->name,
                    "entity_type" => $entity->entity_type,
                    "route_id" => null,
                    "route" => "",
                );

                continue;
            }


            $values[] = array(
                "id" => $entity->id,
                "name" => $entity->name,
                "entity_type" => $entity->entity_type,
                "route_id" => $entity->route_id,
                "route" => $entity->origin . "/" . $entity->destination,
            );
        }

        return response()->json(array(
            "code" => 200,
            "data" => $values
        ));
    }

    public function getEntity($id)
    {
        $entity = Entities::find($id);
        if (is_null($entity)) {
            return response()->json(array(
                "code" => 404,
                "msg" => "Entity was not found"
            ));
        }

        return response()->json(array(
            "code" => 200,
            "data" => $entity
        ));
    }

    public function updateEntity(Request $request, $id)
    {
        $entity = Entities::find($id);
        if (is_null($entity)) {
            return response()->json(array(
                "code" => 404,
                "msg" => "Entity was not found",
            ));
        }

        $data = json_decode($request->getContent(), true);

        if (0 == strlen(trim($data["name"]))) {
            return response()->json(array(
                "code" => 304,
                "msg" => "Entity Name can't be empty",
            ));
        }
        if (0 == strlen(trim($data["entity_type"]))) {
            return response()->json(array(
                "code" => 304,
                "msg" => "Entity Entity Type can't be empty",
            ));
        }

        /**
         * Entity type 0 (Driver) has route to walk at simulator. Let's verify this exists
         */
        if ($data["entity_type"] == 0 && isset($data["route_id"])) {
            if (0 == strlen(trim($data["route_id"]))) {
                return response()->json(array(
                    "code" => 304,
                    "msg" => "Entity Route can't be empty",
                ));
            }
        }

        $entity->name = $data["name"];
        $entity->entity_type = $data["entity_type"];

        if (!is_null($data["route_id"])) {
            $entity->route_id = $data["route_id"];
        }

        $entity->save();

        return response()->json(array(
            "code" => 200,
            "msg" => "Entity was updated successfully",
        ));
    }

    /**
     * JSON response
     */
    public function getRoutePoints($id)
    {
        $points = DB::table("bb_routes")
            ->join("bb_points", "bb_routes.id", "=", "bb_points.route_id")
            ->select("bb_points.id", "bb_points.lat", "bb_points.lng")
            ->where("bb_routes.id", "=", $id)
            ->orderBy("bb_points.id", "asc")
            ->get();

        return response()->json(array(
            "points" => $points
        ));
    }
}