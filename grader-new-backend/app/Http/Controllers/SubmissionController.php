<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

require 'backend-compilador/upload.php';

use App\submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DB::table('alumno_submission_intento')
            ->join('submissions', function ($join){
                $join->on('submissions.assignment_id', '=', 'alumno_submission_intento.assignment_id');
            })
            ->join('assignments', function ($join) {
                $join->on([
                    ['assignments.tries','>','alumno_submission_intento.tries_left'],
                    ['assignments.assignment_id','=','alumno_submission_intento.assignment_id'],
                ]);
            })            
            ->join('languages','languages.language_id','=','assignments.language')
            ->join('groups','groups.crn','=','assignments.crn')
            ->select('groups.name as group_name','assignments.*','alumno_submission_intento.*','languages.*','submissions.updated_at','submissions.grade')
            ->orderBy('submissions.updated_at','desc')
            ->limit(50)
            ->get();
    }

    public static function uploadSubmission($id,$grade,$user_id)
    {
        $try = DB::table('alumno_submission_intento')->where([
            ['assignment_id', '=',$id],
            ['id', '=',$user_id],
        ])->value('tries_left');
        
        if($try > 0){
            DB::table('submissions')->insert([
                'assignment_id' => $id,
                'grade' => $grade,
                'user_id' => $user_id,
            ]);
            return  json_encode([$grade]);
        }
        else{
            return json_encode(["Maximum number of tries used"]);
        }
    }

    public static function getLanguage($id)
    {
        return DB::table('languages')->where([
            ['language_id', '=',$id]
        ])->value('language');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        return compile();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function showAssignment($id)
    {
        return DB::table('alumno_submission_intento')->where('assignment_id','=',$id)->orderBy('id','asc')->get();
            
    }

    public function csv($id)
    {
 
        $users = DB::table('alumno_submission_intento')->where('assignment_id','=',$id)->orderBy('id','asc')->get();
 
        foreach ($users as $user) {
 
                $userData[] = [
                    'Matricula' => $user->id,
                    'Calificacion' => $user->grade,
                ];
            }
 
        // Generate and return the spreadsheet
        Excel::create('users', function ($excel) use ($userData) {
 
            // Build the spreadsheet, passing in the users array
            $excel->sheet('sheet1', function ($sheet) use ($userData) {
                $sheet->fromArray($userData);
            });
 
        })->download('csv');
    }

    public function show($id)
    {
        return DB::table('alumno_submission_intento')
            ->join('submissions', function ($join) use ($id){
                $join->on('submissions.assignment_id', '=', 'alumno_submission_intento.assignment_id')
                ->where('alumno_submission_intento.id','=',$id);
            })
            ->join('assignments', function ($join) {
                $join->on([
                    ['assignments.tries','>','alumno_submission_intento.tries_left'],
                    ['assignments.assignment_id','=','alumno_submission_intento.assignment_id'],
                ]);
            })            
            ->join('languages','languages.language_id','=','assignments.language')
            ->join('groups','groups.crn','=','assignments.crn')
            ->select('groups.name as group_name','assignments.*','alumno_submission_intento.*','languages.*','submissions.updated_at','submissions.grade')
            ->orderBy('submissions.updated_at','desc')
            ->limit(50)
            ->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
