<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use function Laravel\Prompts\text;

class ExcelImportController extends Controller
{
    public function test(Request $request){
        // $request->validate([
        //     'file' => 'required'
        // ]);
        // $file = $request->file('file');
        // $rows = Excel::toCollection([] , $file)[0];
        $start = microtime(true) ;
        sleep(1) ;
        $end = microtime(true) ;
        return  $end - $start;
    }
}
