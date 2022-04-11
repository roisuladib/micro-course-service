<?php

namespace App\Http\Controllers;

use App\Course;
use App\MyCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MyCourseController extends Controller
{
    public function index(Request $request) {
        $myCourse = MyCourse::query()->with('course');
        $userId = $request->query('user_id');
        $myCourse->when($userId, function($query) use ($userId) {
            return $query->where('users_id', '=', $userId);
        });
        return response()->json([
            'status' => 'success',
            'data' => $myCourse->get()
        ]);
    }

    public function create(Request $request) {
        $rules = [
            'courses_id' => 'required|integer',
            'users_id' => 'required|integer'
        ];
        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400); 
        }
        $courseId = $request->input('courses_id');
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }
        $userId = $request->input('users_id');
        $user = getUser($userId);
        if ($user['status'] === 'error') {
            return response()->json([
                'status' => $user['status'],
                'message' => $user['message']
            ], $user['http_code']) ;
        }
        
        $isExist = MyCourse::where('courses_id', '=', $courseId)
            ->where('users_id', '=', $userId)->exists(); 
        if ($isExist) {
            return response()->json([
                'status' => 'error',
                'message' => 'User already take this course'
            ], 409);
        }

        if ($course->type === 'premium') {
            if ($course->price === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Price Can\'t be Rp. 0'
                ], 405);
            }
            $order = postOrder([
                'user' => $user['data'],
                'course' => $course->toArray()
            ]);
            if ($order['status'] === 'error') {
                return response()->json([
                    'status' => $order['status'],
                    'message' => $order['message']
                ], $order['http_code']);
            }
            return response()->json([
                'status' => $order['status'],
                'message' => 'Created premium course',
                'data' => $order['data']
            ]);           
        }
        else {
            $myCourse = MyCourse::create($data);
            return response()->json([
                'status' => 'success',
                'message' => 'Created My Course',
                'data' => $myCourse
            ]);
        }
    }

    public function createPremiumAccess(Request $request) {
        $data = $request->all();
        $myCourse = MyCourse::create($data);
        return response()->json([
            'status' => 'success',
            'message' => 'Premium class created',
            'data' => $myCourse
        ], 201);
    } 
}
