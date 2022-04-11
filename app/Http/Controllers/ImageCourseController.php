<?php

namespace App\Http\Controllers;

use App\ImageCourse;
use App\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageCourseController extends Controller
{
    public function create(Request $request) {
        $rules = [
            'image' => 'required|url',
            'courses_id' => 'required|integer'
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
        $imageCourse = ImageCourse::create($data);
        return response()->json([
            'status' => 'success',
            'message' => 'Image course created',
            'data' => $imageCourse
        ]);
    }
    public function destroy($id) {
        $imageCourse = ImageCourse::find($id);
        if (!$imageCourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'Image course not found'
            ], 404);
        }
        $imageCourse->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Deleted image course'
        ]);
    }
}
