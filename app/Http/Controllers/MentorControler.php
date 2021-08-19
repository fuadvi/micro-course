<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MentorControler extends Controller
{

    public function index()
    {
        $mentors = Mentor::all();
        return response()->json(
            [
                'status' => 'success',
                'data' => $mentors
            ]
        );
    }

    public function show($id)
    {
        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => 'Mentor Not Found'
                ],
                404
            );
        }

        return response()->json(
            [
                'status' => 'success',
                'data' => $mentor
            ]
        );
    }

    public function create(Request $request)
    {
        $rule = [
            'name' => 'required|string',
            'profile' => 'required|url',
            'profession' => 'required|string',
            'email' => 'required|email',
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rule);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => $validator->errors()
                ],
                400
            );
        }

        $mentor = Mentor::create($data);

        return response()->json(['status' => 'success', 'data' => $mentor]);
    }

    public function update(Request $request, $id)
    {

        $rule = [
            'name' => 'string',
            'profile' => 'url',
            'profession' => 'string',
            'email' => 'email',
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rule);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => $validator->errors()
                ],
                400
            );
        }

        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => 'Mentor Not Found'
                ],
                404
            );
        }

        $mentor->fill($data);
        $mentor->save();

        return response()->json(['status' => 'success', 'data' => $mentor]);
    }

    public function destroy($id)
    {
        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => 'Mentor Not Found'
                ],
                404
            );
        }

        $mentor->delete();

        return response()->json(
            [
                'status' => 'success',
                'massage' => 'mentor deleted'
            ]
        );
    }
}
