<?php

namespace App\Http\Controllers;

use App\Chapter;
use App\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    public function index(Request $request) {
        $chapter = Lesson::query();
        $chapterId = $request->input('chapter_id');
        $chapter->when($chapterId, function($query) use ($chapterId) {
             return $query->where('chapters_id', '=', $chapterId);
        });
        return response()->json([
            'status' => 'success',
            'message' => 'List data lesson',
            'data' => $chapter->get()
        ]);
    }

    public function show($id) {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lesson not found!'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $lesson
        ]);
    }

    public function create(Request $request) {
        $rules = [
            'name' => 'required|string', 
            'video' => 'required|string', 
            'chapters_id' => 'required|integer'
        ];
        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }
        $chapterId = $request->input('chapters_id');
        $chapter = Chapter::find($chapterId);
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'Chapter not found!'
            ], 404);
        }
        $lesson = Lesson::create($data);
        return response()->json([
            'status' => 'success',
            'message' => 'Created',
            'data' => $lesson
        ]);
    }

    public function update(Request $request, $id) {
        $rules = [
            'name' => 'string', 
            'video' => 'string', 
            'chapters_id' => 'integer'
        ];
        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lesson not found!'
            ], 404);
        }
        $chapterId = $request->input('chapters_id');
        if ($chapterId) {
            $chapter = Chapter::find($chapterId);
            if (!$chapter) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Chapter not found!'
                ], 404);
            }
        }
        $lesson->fill($data);
        $lesson->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Updated',
            'data' => $lesson
        ]);
    }

    public function destroy($id) {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lesson not found!'
            ], 404);
        }
        $lesson->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Deleted Lesson'
        ]);
    }
}
