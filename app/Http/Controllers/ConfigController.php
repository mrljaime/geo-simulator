<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 04/03/17
 * Time: 19:59
 */

namespace App\Http\Controllers;


use App\Models\AppConfig;
use App\Models\Entities;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    /**
     * Gets app global config.
     */
    public function index()
    {
        $appConfig = AppConfig::all()->first();
        $maxCars = Entities::where("entity_type", 0)->count();
        $maxDrivers = Entities::where("entity_type", 1)->count();


        return response()->json(array(
            "code" => 200,
            "data" => array(
                "config" => $appConfig,
                "maxCars" => $maxCars,
                "maxDrivers" => $maxDrivers,
            ),
        ));
    }

    public function update(Request $request)
    {
        $maxCars = Entities::where("entity_type", 0)->count();
        $maxDrivers = Entities::where("entity_type", 1)->count();
        $data = json_decode($request->getContent(), true);

        if ($data["config"]["maxCars"] > $maxCars || strlen(trim($data["config"]["maxCars"])) == 0) {
            return response()->json(array(
                "code" => 304,
                "msg" => sprintf("The max number cars has ben exceeded (%s)", $maxCars),
            ));
        }
        if ($data["config"]["maxDrivers"] > $maxDrivers || strlen(trim($data["config"]["maxDrivers"])) == 0) {
            return response()->json(array(
                "code" => 304,
                "msg" => sprintf("The max number drivers has been exceeded (%s)", $maxDrivers),
            ));
        }

        $appConfig = AppConfig::all()->first();
        $appConfig->max_clients = $data["config"]["maxCars"];
        $appConfig->max_users = $data["config"]["maxDrivers"];
        $appConfig->state = $data["config"]["state"];

        $appConfig->save();

        return response()->json(array(
            "code" => 200,
            "msg" => "Save config successfully"
        ));
    }
}