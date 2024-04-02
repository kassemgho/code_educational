<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\ContestStudent;
use App\Models\Problem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Trend\Trend;

class ContestController extends Controller
{
    public function create(Request $request){
        $request->validate([
            'name' => 'required|string',
            'duration' => 'integer',
            'start_at' => 'date',
            'hour' => 'required|integer',
            'min_level' => 'required|integer|min:1|max:10',
            'max_level' => 'required|integer|min:0|max:10',
        ]);
        $scour = 0 ;
        $contest = Contest::create($request->all());
        $problems = Problem::whereBetween('level', [$request->min_level, $request->max_level])
                    ->inRandomOrder()
                    ->take(5)
                    ->get();
        foreach($problems as $problem){
            $scour +=$problem->level ;
            $contest->problems()->attach($problem->id);
        }
        $contest->scour = $scour ;
        $contest->save() ;
        return response()->json([
            'message' => "contest will start at $request->start_at",
        ],200);
    }
    public function solve(Contest $contest , Problem $problem,Request $request){
        $currentDateTime = now();

        if ($currentDateTime < $contest->start_at)
            return response()->json([
                'message' => 'contest not start yet' 
            ]);
        else if ($currentDateTime > $contest->start_at)
            return response()->json([
                'message' => 'contest is over'
            ]);
        else return 'contest is active ';
       $solve = ProblemController::solve($problem , $request); 
       if ($solve['aproved' == true]){
            $student =  auth()->user()->student ;
            $currentRank = $student->contests()->wherePivot('contest_id', $contest->id)->value('rank');
            $newRank  = $currentRank + $problem->level ;
            $student->contests()->updateExistingPivot($contest->id , ['rank' => $newRank]) ;
       }
       return $solve ;
    }
    
}
