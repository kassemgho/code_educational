<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use Illuminate\Support\Facades\DB;

class ClassclcearController extends Controller
{
    public static function kassem() {
    $query = "SELECT * FROM users";
    $data = DB::select(DB::raw($query));
    $users = DB::select("SELECT * FROM users");
    return $users;
    //    return DB::select(DB::raw("select * from users") );
       
    }
}
