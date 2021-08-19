<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'user_id' => 'required|integer',
            'course_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'note' => 'string'
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

        $courseId = $request->course_id;
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => 'course not found'
                ],
                404
            );
        }

        $userId = $request->user_id;
        $user = getUser($userId);
        if ($user['status'] === 'error') {
            return response()->json(
                [
                    'status' => $user['status'],
                    'massage' => $user['massage']
                ],
                $user['http_code']
            );
        }

        $isExistReviews = Review::where('course_id', '=', $courseId)
            ->where('user_id', '=', $userId)
            ->exists();

        if ($isExistReviews) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => 'review already exits'
                ],
                409
            );
        }

        $review = Review::create($data);
        return response()->json(
            [
                'status' => 'success',
                'data' => $review
            ]
        );
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'rating' => 'integer|min:1|max:5',
            'note' => 'string'
        ];

        $data = $request->except('user_id', 'course_id');

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

        $review = Review::find($id);
        if (!$review) {
            return response()->json(
                [
                    'status' => "error",
                    'massage' => 'review not found'
                ],
                404
            );
        }

        $review->fill($data);
        $review->save();

        return response()->json(
            [
                'status' => "success",
                'data' => $review
            ]
        );
    }

    public function destroy($id)
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json(
                [
                    'status' => "error",
                    'massage' => 'review not found'
                ],
                404
            );
        }
        $review->delete();
        return response()->json(
            [
                'status' => "success",
                'massage' => 'review deleted'
            ]
        );
    }
}
