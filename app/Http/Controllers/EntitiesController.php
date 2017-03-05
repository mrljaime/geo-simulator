<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 15/10/16
 * Time: 11:13 AM
 */

namespace App\Http\Controllers;

use App\Entities;
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
            ->orderBy("aa_entities.name", "asc")
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

    public function getEntities(Request $request)
    {
        $draw = $request->input("draw");
        $limit = $request->input("length");
        $offset = $request->input("start");
        $order = $request->input("order");
        $columns = $request->input("columns");
        $dir = $order[0]["dir"];
        $orderBy = $columns[$order[0]["column"]]["data"];

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
            ->orderBy($orderBy, $dir)
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
                "route" => $entity->origin . " / " . $entity->destination,
            );
        }

        return response()->json(array(
            "draw" => $draw,
            "data" => $values,
            "recordsTotal" => count($values),
            "recordsFiltered" => count($values),
        ));
    }

    public function editEntities(Request $request, $id)
    {
        $entity = Entities::find($id);

        /************************************
         * WARNING:
         *      This because browsers doesn't support put method
         ************************************/
        Log::debug($request->input("_method"));

        if (strtolower($request->input("_method")) == "put") {

            $name = $request->input("name");
            $lat = $request->input("lat");
            $lng = $request->input("lng");
            $route = $request->input("routeId");

            if ($entity->entity_type == 1) {
                $entity->route_id = $route;
            }
            $entity->name = $name;
            $entity->lat = $lat;
            $entity->lng = $lng;
            $entity->save();
        }

        /** Is a driver */
        $routes = null;
        if ($entity->entity_type == 1) {
            $routes = Routes::all();
        }


        return view("entities.edit", array(
            "entity" => $entity,
            "title" => $entity->name,
            "routes" => $routes,
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