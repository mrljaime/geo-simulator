<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 04/03/17
 * Time: 19:03
 */

namespace App\Http\Controllers;


use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    public function index()
    {
        View::addExtension("html", "php");
        return View::make("home");
    }
}