<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class entities extends Model
{
    /*************************
     * WARNING :
     *      The $table variable matches with the database table name
     *************************/
    protected $table = "aa_entities";

    public function route()
    {
        return $this->hasOne("App\\Routes", "id", "route_id");
    }
}
