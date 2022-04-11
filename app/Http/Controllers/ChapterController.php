<?php

namespace App\Http\Controllers;

use App\Chapter;
use App\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChapterController extends Controller
{
    public function index(Request $request) {
        $chapter = Chapter::query();
        $courseId = $request->query('course_id');
        $chapter->when($courseId, function($query) use ($courseId) {
             return $query->where('courses_id', '=', $courseId);
        });
        return response()->json([
            'status' => 'success',
            'data' => $chapter->get()
        ]);
    }

    public function create(Request $request) {
        $rules = [
            'name' => 'required|string', 
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
        $courserId = $request->input('courses_id');
        $course = Course::find($courserId);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found!'
            ], 404);
        } 
        $chapter = Chapter::create($data);
        return response()->json([
            'status' => 'success',
            'message' => 'Created',
            'data' => $chapter
        ]);
    }

    public function update(Request $request, $id) {
        $rules = [
            'name' => 'string', 
            'courses_id' => 'integer'
        ];
        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }
        $chapter = Chapter::find($id);
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'Chapter not found!'
            ], 404);
        }
        $courseId = $request->input('courses_id');
        if ($courseId) {
            $course = course::find($courseId);
            if (!$course) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Course not found!'
                ], 404);
            }
        }
        $chapter->fill($data);
        $chapter->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Updated',
            'data' => $chapter
        ]);
    }

    public function show($id) {
        $chapter = Chapter::find($id);
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'Chapter not found!'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $chapter
        ]);
    }

    public function destroy($id) {
        $chapter = Chapter::find($id);
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'Chapter not found!'
            ], 404);
        }
        $chapter->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Deleted chapter'
        ]);
    }
}
