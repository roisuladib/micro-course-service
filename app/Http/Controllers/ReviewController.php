<?php

namespace App\Http\Controllers;

use App\Course;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function create(Request $request) {
        $rules = [
            'users_id' => 'required|integer',
            'courses_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'note' => 'string'
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
            ], $user['http_code']);
        }
        $isExistReview = Review::where('courses_id', '=', $courseId)
            ->where('users_id', '=', $userId)
            ->exists();
        if ($isExistReview) {
            return response()->json([
                'status' => 'error',
                'message' => 'Review already exist'
            ], 409);
        }
        $review = Review::create($data);
        return response()->json([
            'status' => 'success',
            'message' => 'Created Review',
            'data' => $review
        ]);
    }

    public function update(Request $request, $id) {
        $rules = [
            'rating' => 'integer|min:1|max:5',
            'note' => 'string'
        ];
        $data = $request->except('users_id', 'courses_id');
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }
        $review = Review::find($id);
        if (!$review) {
            return response()->json([
                'status' => 'error',
                'message' => 'Review not found'
            ], 404);
        }
        $review->fill($data);
        $review->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Updated review',
            'data' => $review
        ]);
    }

    public function destroy($id) {
        $review = Review::find($id);
        if (!$review) {
            return response()->json([
                'status' => 'error',
                'message' => 'Review not found'
            ], 404);
        }
        $review->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Review deleted'
        ]);
    }
}
