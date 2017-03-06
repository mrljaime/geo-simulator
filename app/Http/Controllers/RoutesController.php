<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 05/03/17
 * Time: 14:21
 */

namespace App\Http\Controllers;


use App\Models\Routes;

class RoutesController extends Controller
{
    public function getRoutes()
    {
        $routes = Routes::all();

        $values = array();
        foreach($routes as $route) {
            $values[] = array(
                "id" => $route->id,
                "name" => sprintf("%s/%s", $route->origin, $route->destination),
            );
        }

        return response()->json(array(
            "code" => 200,
            "data" => $values
        ));
    }

    public function getRoutePoints($id)
    {

    }
}