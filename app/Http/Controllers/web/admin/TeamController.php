<?php

namespace App\Http\Controllers\web\admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $team = Team::withCount('teamUsers')->get();
        return view('admin.team.index', ['teams' => $team]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        try {
            Team::create([
                'team_name' => $request->team_name,
                'team_type' => $request->team_type,
            ]);

            return redirect()
                ->route('team.index',app()->getLocale())
                ->with('success', 'Team Added');
        } catch (\Throwable $th) {
            return redirect()
                ->route('team.index' ,app()->getLocale())
                ->with('failed', 'Request Failed');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function destroy($lang ,$id)
    {
        //
        try {
            User::where('id_team', $id)->update(['id_team' => "0"]);

            Team::find($id)->delete();
            return redirect()
            ->route('team.index' ,app()->getLocale())
            ->with('success', 'Team Removed');
    } catch (\Throwable $th) {
        // return $th->getMessage();
        return redirect()
            ->route('team.index' ,app()->getLocale())
            ->with('failed', 'Request Failed');
    }
    }
}
