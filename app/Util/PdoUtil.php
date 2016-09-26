<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 24/09/16
 * Time: 10:19 PM
 */

namespace app\Util;


use Illuminate\Support\Facades\Log;
use Mockery\CountValidator\Exception;

class PdoUtil
{
    public static function selectPrepared($conn, $stmt, array $params = null)
    {
        $pstmt = $conn->prepare($stmt);
        if (!is_null($params)) {
            $keys = array_keys($params);
            foreach ($keys as $key) {
                $pstmt->bindParam(":" . $key, $params[$key]);
            }
        }

        $pstmt->execute();

        $result = $pstmt->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    public static function selectSingleOrNullPrepared($conn, $stmt, array $params = null)
    {
        $pstmt = $conn->prepare($stmt);
        if (!is_null($params)) {
            $keys = array_keys($params);
            foreach ($keys as $key) {
                $pstmt->bindParam(":" . $key, $params[$key]);
            }
        }
        $pstmt->execute();
        $result = $pstmt->fetchAll(\PDO::FETCH_ASSOC);

        if (count($result) == 0) {
            return null;
        } else if (count($result) > 1) {
            throw new Exception("There was more that 1 rows");
        }

        return $result[0];
    }

    public static function executePrepared($conn, $stmt, array $params = null)
    {
        $pstmt = $conn->prepare($stmt);
        if (!is_null($params)) {
            $keys = array_keys($params);
            foreach ($keys as $key) {
                $pstmt->bindParam(":" . $key, $params[$key]);
            }
        }

        $pstmt->execute();
    }

}