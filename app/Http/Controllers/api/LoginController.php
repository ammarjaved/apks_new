<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    //

    public function login(Request $req){

        try {

        $validator = Validator::make($req->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 400,
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }
        $input = $req->all();


        if (  auth()->attempt(['name' => $input['username'], 'password' => $input['password']])) {
            $user = Auth::user();
             $team = Team::find($user->id_team);
            $user['team'] = $team->team_name;


            return response()
                    ->json([
                        'statusCode' => 200,
                        'success'=>true,
                        'message' => 'login success',
                        'data'=>$user,

                    ],200);
        } else {

            return response()
                    ->json([
                        'statusCode' => 404,
                        'success'=>false,
                        'message' => 'user not found',
                    ]);
        }


        } catch (\Throwable $th) {
            return response()
                    ->json([
                        'statusCode' => 500,
                        'success'=>false,
                        'message' => 'Server error',
                        'error'=>$th->getMessage()
                    ]);
        }
    }

    public function test(){
        return "Asdas";
    }
}
