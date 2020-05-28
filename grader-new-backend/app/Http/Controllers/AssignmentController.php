<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\assignment;
use App\group;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

require 'backend-compilador/uploadAssignment.php';

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        //
        return group::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
     public static function createAssignment($nombre,$crn,$start_date,$end_date,$tries,$language){
        DB::table('assignments')->insert([
            'crn' => $crn,
            'name' => $nombre,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'tries' => $tries,
            'language' => $language
        ]);
    }

    public function store(Request $request)
    {
        //
        compile();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function show(int $group)
    {
        //
        try{
            return group::findOrFail($group);
        }
        catch(ModelNotFoundException $e){
            return response(
                json_encode(array('error' => true, 'error_message' => $e->getMessage()))
                , 404)->header('Content-type', 'application/json');
        }
    }

    public function showTeacher($id)
    {
        //
        try{
            $collection = group::all();
            return $collection->where('professor_id','=',$id);
        }
        catch(ModelNotFoundException $e){
            return response(
                json_encode(array('error' => true, 'error_message' => $e->getMessage()))
                , 404)->header('Content-type', 'application/json');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, assignment $assignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function destroy(assignment $assignment)
    {
        //
    }
}
