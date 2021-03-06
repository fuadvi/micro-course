<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Mentor;
use App\Models\MyCourse;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::query();

        $q = $request->query('q');
        $status = $request->query('status');

        $courses->when($q, function ($query) use ($q) {
            return $query->whereRaw("name LIKE '%" . strtolower($q) . "%'");
        });

        $courses->when($status, function ($query) use ($status) {
            return $query->where("status", "=", $status);
        });

        return response()->json(
            [
                'status' => 'success',
                'data' => $courses->paginate(5)
            ]
        );
    }

    public function show($id)
    {
        $course = Course::with('mentor')
            ->with('chapters.lessons')
            ->with('images')
            ->find($id);

        if (!$course) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => 'course not found'
                ],
                404
            );
        }

        $reviews = Review::where('course_id', '=', $id)->get()->toArray();

        if (count($reviews) > 0) {
            $userIds = array_column($reviews, 'user_id');
            $users = getUserByIds($userIds);

            // echo "<pre>" . print_r($users, 1) . "</pre>";
            if ($users['status'] === 'error') {
                $reviews = [];
            } else {
                foreach ($reviews as $key => $review) {
                    $userIndex = array_search($review['user_id'], array_column($users['data'], 'id'));
                    $reviews[$key]['users'] = $users['data'][$userIndex];
                }
            }
        }

        $totalStudent = MyCourse::where('course_id', '=', $id)->count();
        $totalVideo = Chapter::where('course_id', '=', $id)->withCount('lessons')->get()->toArray();
        $FinaltotalVideo = array_sum(array_column($totalVideo, 'lessons_count'));

        $course['reviews'] = $reviews;
        $course['total_student'] = $totalStudent;
        $course['total_video'] = $FinaltotalVideo;

        return response()->json(
            [
                'status' => 'success',
                'data' => $course
            ]
        );
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'certificate' => 'required|boolean',
            'thumbnail' => 'required|url',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:draf,published',
            'price' => 'integer',
            'level' => 'required|in:all-level,beginner,intermediate,advance',
            'mentor_id' => 'required|integer',
            'decription' => 'string'
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

        $mentorId = $request->mentor_id;
        $mentor = Mentor::find($mentorId);

        if (!$mentor) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => 'mentor not found'
                ],
                404
            );
        }

        $course = Course::create($data);

        return response()->json(
            [
                'status' => 'success',
                'data' => $course
            ]
        );
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string',
            'certificate' => 'boolean',
            'thumbnail' => 'url',
            'type' => 'in:free,premium',
            'status' => 'in:draf,published',
            'price' => 'integer',
            'level' => 'in:all-level,beginner,intermediate,advance',
            'mentor_id' => 'integer',
            'decription' => 'string'
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

        $course = Course::find($id);
        if (!$course) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => 'Course Not Found'
                ],
                404
            );
        }

        $mentorId = $request->mentor_id;
        if ($mentorId) {
            $mentor = Mentor::find($mentorId);
            if (!$mentor) {
                return response()->json(
                    [
                        'status' => 'error',
                        'massage' => 'Mentor Not Found'
                    ],
                    404
                );
            }
        }

        $course->fill($data);
        $course->save();

        return response()->json(
            [
                'status' => 'succses',
                'data' => $course
            ]
        );
    }

    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => 'Course Not Found'
                ],
                404
            );
        }

        $course->delete();

        return response()->json(
            [
                'status' => 'success',
                'massage' => 'course deleted'
            ]
        );
    }
}
