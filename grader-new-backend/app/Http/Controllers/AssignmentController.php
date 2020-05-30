<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\assignment;
use DateTime;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Mockery\Generator\StringManipulation\Pass\Pass;

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
        $query = assignment::all();
        $date = date("Y-m-d H:i:s");
        foreach($query as $assignment){
            if($assignment->end_date < $date){
                $assignment->active = false;
                DB::table('assignments')
                ->where('assignment_id', $assignment->assignment_id)
                ->update(['active' => false]);
            }
        }
        return $query;
    }

    public static function getAll(){
        $query = assignment::all();
        $date = date("Y-m-d H:i:s");
        foreach($query as $assignment){
            if($assignment->end_date < $date){
                $assignment->active = false;
                DB::table('assignments')
                ->where('assignment_id', $assignment->assignment_id)
                ->update(['active' => false]);
            }
        }
        return $query;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public static function delete($idAss)
    {
        DB::table('assignments')->where('assignment_id', '=', $idAss)->delete();
    }

    public static function createAssignment($nombre, $crn, $start_date, $end_date, $tries, $language)
    {
        $data = assignment::create([
            'crn' => $crn,
            'name' => $nombre,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'tries' => $tries,
            'language' => $language
        ]);
        return $data->assignment_id;
    }

    public function store(Request $request)
    {
        //
        return compile();
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
        try {
            return assignment::findOrFail($group);
        } catch (ModelNotFoundException $e) {
            return response(
                json_encode(array('error' => true, 'error_message' => $e->getMessage())),
                404
            )->header('Content-type', 'application/json');
        }
    }

    public function showTeacher($professor_id)
    {
        try {
            $crn = DB::table('professor_group')->where([
                ['professor_id', '=', $professor_id],
            ])->pluck('crn');
            $json = [];
            $collection = $this->getAll();
            foreach ($crn as $currentCrn) {
                foreach ($collection->where('crn', '=', $currentCrn) as $assignment) {
                    array_push($json, $assignment);
                }
            }
            return json_encode($json);
        } catch (ModelNotFoundException $e) {
            return response(
                json_encode(array('error' => true, 'error_message' => $e->getMessage())),
                404
            )->header('Content-type', 'application/json');
        }
    }

    public function showStudent($user_id)
    {
        try {
            $crn = DB::table('student_group')->where([
                ['user_id', '=', $user_id],
            ])->pluck('crn');
            $json = [];
            $collection = $this->getAll();
            foreach ($crn as $currentCrn) {
                foreach ($collection->where('crn', '=', $currentCrn) as $assignment) {
                    array_push($json, $assignment);
                }
            }
            return json_encode($json);
        } catch (ModelNotFoundException $e) {
            return response(
                json_encode(array('error' => true, 'error_message' => $e->getMessage())),
                404
            )->header('Content-type', 'application/json');
        }
    }

    public function showAssignment($id)
    {
        //
        try {
            $collection = $this->getAll();
            return $collection->where('assignment_id', '=', $id);
        } catch (ModelNotFoundException $e) {
            return response(
                json_encode(array('error' => true, 'error_message' => $e->getMessage())),
                404
            )->header('Content-type', 'application/json');
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
