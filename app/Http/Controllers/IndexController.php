<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 24/09/16
 * Time: 05:13 PM
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Util\PdoUtil;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;


class IndexController extends Controller
{
    public function index()
    {
        return view("index");
    }

    /**
     * Here's where dataTable get by ajax the list of entities and his more closest driver
     * Has too the responsibility to check the config of the app and store the values
     *      that handle who can access to the app via API to change routing of each one
     */
    public function getEntitiesList(Request $request)
    {

        $conn = DB::connection()->getPdo();

        /** To prevent that users changes config when this is executing */

        /********************
         * BEGIN TRANSACTION
        ********************/
        $conn->beginTransaction();

        $appConfig = PdoUtil::selectPrepared($conn,
            "SELECT max_clients, max_users, state FROM aa_app FOR UPDATE")[0];
        $clientIds = PdoUtil::selectPrepared($conn,
            "SELECT id FROM aa_entities WHERE entity_type = 0 LIMIT {$appConfig["max_clients"]}");
        $userIds = PdoUtil::selectPrepared($conn,
            "SELECT id FROM aa_entities WHERE entity_type = 1 LIMIT {$appConfig["max_users"]}");


        /********************
         * COMMIT
         ********************/
        $conn->commit();

        /** I consider more efficient that delete when id are not in id's and then make a upsert.
         *  That's because I prefer delete all and the insert each entity
         */
        PdoUtil::executePrepared($conn, "DELETE FROM cc_active");

        $clientKeys = array();
        $userKeys = array();
        foreach ($clientIds as $clientId) {
            $stmt = "
            INSERT INTO cc_active (entity_id)
            VALUES (:id)
            ";
            PdoUtil::executePrepared($conn, $stmt, array(
                "id" => $clientId["id"]
            ));
            $clientKeys[] = $clientId["id"];
        }

        foreach ($userIds as $userId) {
            $stmt = "
            INSERT INTO cc_active (entity_id)
            VALUES (:id)
            ";
            PdoUtil::executePrepared($conn, $stmt, array(
                "id" => $userId["id"]
            ));
            $userKeys[] = $userId["id"];
        }


        /** After insert the active users and clients, we will put in the list and then wait for update */

        $clientKeys = implode(",", $clientKeys);
        $stmt = "
        SELECT
          id,
          name,
          lat,
          lng
        FROM aa_entities
        WHERE entity_type = 0
        AND id IN ($clientKeys)
        "; $clients = PdoUtil::selectPrepared($conn, $stmt);


        $userKeys = implode(",", $userKeys);
        foreach ($clients as $key => $value) {

            $stmt = "
            SELECT *
                FROM (
                    SELECT
                      e.id,
                      name,
                      p.lat,
                      p.lng,
                      FORMAT(111 * DEGREES(ACOS(COS(RADIANS({$value["lat"]}))*COS(RADIANS(p.lat))
                                         * COS(RADIANS({$value["lng"]} - p.lng))+SIN(RADIANS({$value["lat"]}))
                                                                                      * SIN(RADIANS(p.lat))))*1000, 1) as distance
                    FROM aa_entities e
                      JOIN bb_routes r ON (e.route_id = r.id)
                      JOIN bb_points p ON (e.last_point_id = p.id AND r.id = p.route_id)
                    WHERE entity_type = 1
                    AND e.id IN ($userKeys)
                ) e
                ORDER BY e.distance DESC
                LIMIT 1
            ";

            $closest = PdoUtil::selectPrepared($conn, $stmt)[0];

            $clients[$key]["closest"] = $closest;
        }

        $draw = $request->input("draw");

        return response()->json(array(
            "draw" => $draw,
            "data" => $clients,
            "recordsTotal" => count($clients),
            "recordsFiltered" => count($clients),
        ));
    }

    public function putNextPosition(Request $request)
    {
        $conn = DB::connection()->getPdo();

        $stmt = "
        SELECT
          id,
          route_id,
          last_point_id
        FROM aa_entities e
        WHERE e.entity_type = 1
        "; $entities = PdoUtil::selectPrepared($conn, $stmt);

        foreach ($entities as $entity) {
            $id = $entity["id"];
            $routeId = $entity["route_id"];
            $lastPointId = $entity["last_point_id"];

            $thisPoint = null;

            $stmt = "
            SELECT
              MAX(id) AS max,
              MIN(id) AS min
            FROM bb_points
            WHERE route_id = $routeId
            ";
            $point = PdoUtil::selectSingleOrNullPrepared($conn, $stmt);

            if ($point["max"] == $lastPointId) {
                $stmt = "
                UPDATE aa_entities SET last_point_id = {$point["min"]}
                WHERE id = $id
                ";
                PdoUtil::executePrepared($conn, $stmt);

                $stmt = "
                SELECT
                  MIN(id) as id
                FROM bb_points
                WHERE route_id = $routeId
                ";
                $thisPoint = PdoUtil::selectSingleOrNullPrepared($conn, $stmt)["id"];
            } else {
                $stmt = "
                UPDATE aa_entities SET last_point_id = {$lastPointId} + 1
                WHERE id = $id
                ";
                PdoUtil::executePrepared($conn, $stmt);

                $thisPoint = $lastPointId + 1;
            }

            $stmt = "
            SELECT
              lat,
              lng
            FROM bb_points
            WHERE id = $thisPoint
            ";
            $thisPoint = PdoUtil::selectSingleOrNullPrepared($conn, $stmt);

            $url = "http://localhost:3000/pushNotification";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                "lat={$thisPoint["lat"]}&lng={$thisPoint["lng"]}&id=$id");

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);

            curl_close($ch);

            Log::info(var_export($server_output));
            sleep(3);
        }



    }
}