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
        date_default_timezone_set('America/Mexico_City');
        $query = assignment::all();
        $date = date("Y-m-d H:i:s");
        foreach($query as $assignment){
            
            $active = false;

            if($assignment->end_date > $date && $assignment->start_date < $date){
                $active = true;
            }

            $assignment->active = $active;
            DB::table('assignments')
            ->where('assignment_id', $assignment->assignment_id)
            ->update(['active' => $active]);
        }
        return $query;
    }

    public static function confirmQuery($query){
        date_default_timezone_set('America/Mexico_City');
        $date = date("Y-m-d H:i:s");

        foreach($query as $assignment){
            
            $active = false;

            if($assignment->end_date > $date && $assignment->start_date < $date){
                $active = true;
            }

            $assignment->active = $active;
            DB::table('assignments')
            ->where('assignment_id', $assignment->assignment_id)
            ->update(['active' => $active]);
        }
        
        return $query;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function delete($id)
    {
        //
        try {
            return DB::table('assignments')->where('assignment_id', '=', $id)->delete();
        } catch (ModelNotFoundException $e) {
            return response(
                json_encode(array('error' => true, 'error_message' => $e->getMessage())),
                404
            )->header('Content-type', 'application/json');
        }
    }

    public static function createAssignment($nombre, $crn, $start_date, $end_date, $tries, $language, $runtime)
    {
        system('echo '.$runtime.' > out');
        $data = assignment::create([
            'crn' => $crn,
            'name' => $nombre,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'tries' => $tries,
            'language' => $language,
            'runtime' => $runtime
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
    public function show(int $assignment)
    {
        //
        try {
            $query = DB::table('assignments')
            ->join('groups', function ($join) use ($assignment){
                $join->on('groups.crn', '=', 'assignments.crn')
                    ->where('assignments.assignment_id', '=', $assignment);
            })
            ->join('languages','languages.language_id','=','assignments.language')
            ->select('groups.name as group_name', 'assignments.*','languages.*')
            ->get();
            return AssignmentController::confirmQuery($query);
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
            $query = DB::table('professor_group')
            ->join('assignments', function ($join) use ($professor_id){
                $join->on('professor_group.crn', '=', 'assignments.crn')
                    ->where('professor_group.professor_id', '=', $professor_id);
            })
            ->join('groups','professor_group.crn','=','groups.crn')
            ->join('languages','languages.language_id','=','assignments.language')
            ->select('groups.name as group_name', 'assignments.*','professor_group.*','languages.*')
            ->get();
            return AssignmentController::confirmQuery($query);
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
            $query = DB::table('student_group')
                ->join('assignments', function ($join) use ($user_id){
                    $join->on('student_group.crn', '=', 'assignments.crn')
                        ->where('student_group.user_id', '=', $user_id);
                })
                ->join('groups','student_group.crn','=','groups.crn')
                ->join('languages','languages.language_id','=','assignments.language')
                ->select('groups.name as group_name', 'assignments.*','student_group.*','languages.*')
                ->get();
            return AssignmentController::confirmQuery($query);
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
