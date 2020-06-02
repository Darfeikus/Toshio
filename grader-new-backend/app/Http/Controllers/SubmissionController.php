<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\submission;
use Illuminate\Http\Request;

use App\assignment;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

require 'backend-compilador/upload.php';


class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DB::table('submissions')
            ->join('alumno_submission_intento', function ($join){
                $join->on('submissions.user_id', '=', 'alumno_submission_intento.id');
            })
            ->join('assignments', function ($join) {
                $join->on([
                    ['assignments.assignment_id','=','submissions.assignment_id'],
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
        DB::beginTransaction();
        try{
            $try = DB::table('alumno_submission_intento')->where([
                ['assignment_id', '=',$id],
                ['id', '=',$user_id],
            ])->value('tries_left');
            
            if($try > 0){
                $insertedId = submission::create([
                    'assignment_id' => $id,
                    'grade' => $grade,
                    'user_id' => $user_id,
                ])->submission_id;
                DB::connection('mysql')->table('submissions')->insert([
                    'submission_id' => $insertedId,
                    'assignment_id' => $id,
                    'grade' => $grade,
                    'user_id' => $user_id,
                ]);
                DB::commit();
                return  json_encode([$grade]);
            }
            else{
                DB::rollBack();
                return json_encode(["Maximum number of tries used"]);
            }
        }
        catch(Exception $e){
            DB::rollBack();
            return json_encode($e->getMessage());
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
