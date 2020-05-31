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

    public function test()
    {
        $_SESSION['id'] = 'A01329173';
        $array = array(
            "Hello" => $_SESSION['id'],
        );
        return json_encode($array);
    }


    public function showTeacherL()
    {
        //
        $array = array(
            "Hello" => $_SESSION['id'],
        );
        return json_encode($array);
    }

    public function index()
    {
        //
        return group::all();
    }

    public static function createGroup($id, $crn, $name, $termcode)
    {
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

    public static function insertStudent($crn, $id)
    {
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
        try {
            return group::findOrFail($group);
        } catch (ModelNotFoundException $e) {
            return response(
                json_encode(array('error' => true, 'error_message' => $e->getMessage())),
                404
            )->header('Content-type', 'application/json');
        }
    }

    public function showTeacher($id)
    {
        //
        try {
            return group::all()->where('professor_id', '=', $id);
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
            return DB::table('student_group')
                ->join('groups', function ($join) use ($user_id){

                    $join->on('student_group.crn', '=', 'groups.crn')
                        ->where('student_group.user_id', '=', $user_id);
                })
                ->get();
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
