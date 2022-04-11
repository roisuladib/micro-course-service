<?php

namespace App\Http\Controllers;

use App\Chapter;
use App\Course;
use App\Mentor;
use App\MyCourse;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index(Request $request) {
        $course = Course::query();
        $q = $request->query('q');
        $status = $request->query('status');

        $course->when($q, function($query) use ($q) {
            return $query->whereRaw("name LIKE '%". strtolower($q) ."%'");
        });
        $course->when($status, function($query) use ($status) {
            return $query->where('status', '=', $status);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'List data courses',
            'data' => $course->paginate(10)
        ]);
    }

    public function show($id) {
        $course = Course::with(['chapter.lesson', 'mentor', 'image'])->find($id);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }
        $reviews = Review::where('courses_id', '=', $id)->get()->toArray();
        if (count($reviews) > 0) {
            $userIds = array_column($reviews, 'users_id');
            $users = getUserById($userIds);
            // echo '<pre>' . print_r($users, 1) . '</pre>';
            if ($users['status'] === 'error') {
                $reviews = []; 
            }
            else {
                foreach ($reviews as $key => $review) {
                    $userIndex = array_search($review['users_id'], array_column($users['data'], 'id'));
                    $reviews[$key]['users'] = $users['data'][$userIndex]; 
                }
            }
        }
        $totalStudent = MyCourse::where('courses_id', '=', $id)->count();
        $totalVideos = Chapter::where('courses_id', '=', $id)->withCount('lesson')->get()->toArray();
        $finalTotalVideos = array_sum(array_column($totalVideos, 'lesson_count'));
        // echo '<pre>' .  print_r($totalVideos, 1) . '</pre>';
        // echo '<pre>' .  print_r($finalTotalVideos, 1) . '</pre>';
        $course['reviews'] = $reviews;
        $course['total_student'] = $totalStudent;
        $course['total_video'] = $finalTotalVideos; 
        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    public function create(Request $request) {
        $rules = [
            'name' => 'required|string',  
            'certificate' => 'required|boolean',  
            'thumbnail' => 'string|url',   
            'type' => 'required|in:free,premium',
            'status' => 'required|in:draft,published',
            'price' => 'integer',
            'level' => 'required|in:all-level,beginner,intermediate,advance',
            'mentors_id' => 'required|integer',
            'description' => 'string'
        ];
        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }
        $mentorId = $request->input('mentors_id');
        $mentor = Mentor::find($mentorId);
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mentor not found!'
            ], 404);
        }
        $course = Course::create($data);
        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    public function update(Request $request, $id) {
        $rules = [
            'name' => 'string',  
            'certificate' => 'boolean',  
            'thumbnail' => 'string|url',   
            'type' => 'in:free,premium',
            'status' => 'in:draft,published',
            'price' => 'integer',
            'level' => 'in:all-level,beginner,intermediate,advance',
            'mentors_id' => 'integer',
            'description' => 'string'
        ];
        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }
        $course = Course::find($id);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found!'
            ], 404);
        }
        $mentorId = $request->input('mentors_id');
        if ($mentorId) {
            $mentor = Mentor::find($mentorId);
            if (!$mentor) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mentor not found!'
                ], 404);
            }
        }
        $course->fill($data);
        $course->save();
        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    public function destroy($id) {
        $course = Course::find($id);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found!'
            ], 404);
        }
        $course->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Deleted course'
        ]);
    }
}
