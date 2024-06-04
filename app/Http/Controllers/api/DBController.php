<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Exception;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\DB;

class DBController extends Controller
{
    //

    public function GetResults(Request $req)
    {
        try {
            $data = DB::select($req->qury);
            // DB::disconnect();
        } catch (Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'failed',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }

        return $req->function_name == 'InsertQuery'
            ? response()->json([
                'success' => true,
                'message' => 'Insert successfully',
                'data' => $data,
            ],)
            : response()->json(
                [
                    'success' => true,
                    'message' => ' successfully',
                    'data' => $data,
                ],
                200,
            );
    }

    public function insert(Request $req)
    {
        try {
            $data = DB::select($req->qury);
            // DB::disconnect();
        } catch (Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'failed',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }

        response()->json(
            [
                'success' => true,
                'message' => 'Insert successfully',
            ],
            200,
        );
    }

    public function update(Request $req)
    {
        try {
            $data = DB::select($req->qury);
            // DB::disconnect();
        } catch (Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'failed',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }

        response()->json(
            [
                'success' => true,
                'message' => 'Update successfully',
            ],
            200,
        );
    }
}
