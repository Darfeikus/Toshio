<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\assignment;
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
    public function show(assignment $assignment)
    {
        //
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
