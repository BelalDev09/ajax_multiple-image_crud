<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        return response()->json([
            'messagae' => 'Category list show successfully',
            'status' => 'success',
            'data' => $categories,
        ], 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'status' => 'in:active,inactive'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation Error',
                'status' => 'error'
            ], 422);
        } else {
            $category = Category::create($request->all());
            return response()->json([
                'data' => $category,
                'message' => 'Category created successfully',
                'status' => 'success'
            ], 201);
        }
    }
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
                'status' => 'error'
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|nullable|string|max:255|unique:categories,slug,' . $id,
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|in:active,inactive'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation Error',
                'status' => 'error'
            ], 422);
        } else {
            $category->update($request->all());
            return response()->json([
                'data' => $category,
                'message' => 'Category updated successfully',
                'status' => 'success'
            ], 200);
        }
    }
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
                'status' => 'error'
            ], 404);
        }
        $category->delete();
        return response()->json([
            'message' => 'Category deleted successfully',
            'status' => 'success'
        ], 200);
    }
}
