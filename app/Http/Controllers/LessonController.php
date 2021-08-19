<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Lessons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{

    public function index(Request $request)
    {
        $lessons = Lessons::query();
        $chapterId = $request->query('chapter_id');

        $lessons->when($chapterId, function ($query) use ($chapterId) {
            $query->where('chapter_id', '=', $chapterId);
        });

        return response()->json(
            [
                'status' => 'success',
                'data' => $lessons->get()
            ]
        );
    }

    public function show($id)
    {
        $lesson = Lessons::find($id);
        if (!$lesson) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => 'Lesson not found'
                ],
                404
            );
        }

        return response()->json(
            [
                'status' => 'success',
                'data' => $lesson
            ]
        );
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'video' => 'required|string',
            'chapter_id' => 'required|integer'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => $validator->errors()
                ],
                400
            );
        }

        $lesson = Lessons::create($data);
        return response()->json(
            [
                'status' => 'success',
                'massage' => $lesson
            ]
        );
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string',
            'video' => 'string',
            'chapter_id' => 'integer'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => $validator->errors()
                ],
                400
            );
        }

        $lesson = Lessons::find($id);
        if (!$lesson) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => 'Lesson not found'
                ],
                404
            );
        }

        $chapterId = $request->chapter_id;
        if ($chapterId) {
            $chapter = Chapter::find($chapterId);
            if (!$chapter) {
                return response()->json(
                    [
                        'status' => 'error',
                        'massage' => 'Chaper not found'
                    ],
                    404
                );
            }
        }

        $lesson->fill($data);
        $lesson->save();

        return response()->json(
            [
                'status' => 'success',
                'data' => $lesson
            ]
        );
    }

    public function destroy($id)
    {
        $lesson = Lessons::find($id);
        if (!$lesson) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => 'Lesson not found'
                ],
                404
            );
        }

        $lesson->delete();
        return response()->json(
            [
                'status' => 'success',
                'data' => 'Lesson Deleted'
            ]
        );
    }
}
