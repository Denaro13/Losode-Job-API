<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobResource;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class JobController extends Controller
{
    public function index(Request $request)
    {
        // if ($request->has('q')) {
        //     return $this->search($request);
        // }

        $jobs = Job::paginate(10);

        return response()->json([
            JobResource::collection($jobs)->response()->getData(true)
        ], 200);
    }

    public function store(Request $request)
    {

        $id = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'company' => 'required|string',
            'company_logo' => 'file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'location' => 'required|string',
            'category' => 'required|string|',
            'salary' => 'required|string|',
            'description' => 'required|string|max:1000',
            'benefits' => 'required|string',
            'type' => 'required|string',
            'work_condition' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $path =  $request->hasFile('company_logo') ? $request->file('company_logo')->store('company_logos', 'public') : null;

        $job = Job::create([
            'user_id' => $id,
            'title' => $request->title,
            'company' => $request->company,
            'company_logo' => $path,
            'location' => $request->location,
            'category' => $request->category,
            'salary' => $request->salary,
            'description' => $request->description,
            'benefits' => $request->benefits,
            'type' => $request->type,
            'work_condition' => $request->work_condition,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Job created successfully',
            'data' => new JobResource($job)
        ], 201);
    }

    public function show(string $id)
    {
        $job = Job::findOrFail($id);

        return response()->json([
            'data' => $job
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::user()->id;
        $job = Job::findOrFail($id);

        if ($userId != $job->user_id) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Unauthorized!',
            ], 403);
        }

        $job->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Job Successfully updated!',
            'data' => $job
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $userId = Auth::user()->id;
        $job = Job::findOrFail($id);

        if ($userId != $job->user_id) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Unauthorized!',
            ], 401);
        }

        $job->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Job Successfully deleted!',
        ], 200);
    }

    public function search(Request $request)
    {
        $query = $request->query('q');

        $jobs = Job::query();

        if ($query) {
            $jobs->where('title', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%");
        }
        $results = $jobs->paginate(10);

        return response()->json([
            'data' => $results
        ], 200);
    }

    public function apply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email',
            'phone' => 'required|string',
            'location' => 'required|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $path = $request->file('cv')->store('cvs', 'public');

        $application = Application::create(
            [
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
                "email" => $request->email,
                "location" => $request->location,
                "phone" => $request->phone,
                "cv" => $path,
                "job_id" => $id,
            ]
        );

        return response()->json([
            'status' => "Success",
            "message" => "Application Successfully Submitted!"
        ], 200);
    }

    public function handleJobs(Request $request)
    {
        if ($request->has('q')) {
            return $this->search($request);
        }

        if (!Auth::guard('sanctum')->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->index($request);
    }
}
