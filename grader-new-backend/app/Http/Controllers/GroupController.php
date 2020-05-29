<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

require 'backend-compilador/uploadGroup.php';

use App\group;
use Illuminate\Http\Request;

class GroupController extends Controller
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

    public static function createGroup($id,$crn,$name,$termcode){
        DB::table('groups')->insert([
            'crn' => $crn,
            'name' => $name,
            'term_code' => $termcode,
            'professor_id' => $id
        ]);

        DB::table('professor_group')->insert([
            'professor_id' => $id,
            'crn' => $crn,
        ]);
    }
    
    public static function insertStudent($crn,$id){
        DB::table('student_group')->insert([
            'user_id' => $id,
            'crn' => $crn,
        ]);
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
     * @param  \App\group  $group
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

    public function showStudent($user_id)
    {
        //
        try{
            $crn = DB::table('student_group')->where([
                ['user_id', '=',$user_id],
            ])->pluck('crn');
            $json = [];
            $collection = group::all();
            foreach($crn as $currentCrn){
                foreach($collection->where('crn','=',$currentCrn) as $group){
                    array_push($json, $group);
                }
            }
            return json_encode($json);
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
     * @param  \App\group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(group $group)
    {
        //
    }
}
