<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseFormatter;
use App\Models\People;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeopleController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 10);

        if ($id) {
            $people = People::find($id);

            if ($people) {
                return ResponseFormatter::success($people, 'Data retrieved successfully.');
            } else {
                return ResponseFormatter::error(null, 'Data retrieved failed.', 404);
            }
        }

        return ResponseFormatter::success(People::paginate($limit), 'Data retrieved successfully.');
    }

    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:people',
            'job' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null, $validator->errors()->first(), 422);
        }

        $validated = $validator->validated();

        $people = People::create($validated);
        if ($people) {
            return ResponseFormatter::success($people, 'Data saved successfully.', 201);
        } else {
            return ResponseFormatter::error(null, 'Data saved failed.', 400);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:people,email,' . $id,
            'job' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null, $validator->errors()->first(), 422);
        }

        $people = People::find($id);

        if (!$people) {
            return ResponseFormatter::error(null, 'User not found.', 404);
        }

        $validated = $validator->validated();

        $people->update($validated);

        return ResponseFormatter::success($people, 'Data updated successfully.');
    }

    public function delete($id)
    {
        $people = People::find($id);

        if (!$people) {
            return ResponseFormatter::error(null, 'Data deleted failed.', 422);
        }

        $people->delete();

        return ResponseFormatter::success($people, 'Data deleted successfully.');
    }
}
