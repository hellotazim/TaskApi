<?php

namespace App\Http\Controllers;

use App\Classes\ResponseWrapper;
use App\Http\Requests\AssignTaskRequest;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }



    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        $returned_data = ResponseWrapper::Start();

        $returned_data['results'] = $this->taskRepository->all($request->only(['status', 'priority', 'due_date', 'sort']));

        return ResponseWrapper::End($returned_data);
    }



    /**
     * Create a new task.
     *
     * @return JsonResponse
     */
    public function store(TaskCreateRequest $request) : JsonResponse
    {
        $returned_data = ResponseWrapper::Start();

        try {
            $returned_data['results'] = $this->taskRepository->create($request->only(['title', 'description', 'status', 'priority', 'due_date']));
            $returned_data['message'] = "Task created successfully";
        } catch (\Exception $exception){
            $returned_data['message'] = $exception->getMessage();
        }

        return ResponseWrapper::End($returned_data);
    }


    /**
     * Show a specific task.
     * @param $id
     * @return JsonResponse
     */
    public function show(Request $request, $id) : JsonResponse
    {
        $returned_data = ResponseWrapper::Start();

        $returned_data['results'] = $this->taskRepository->find($id);

        return ResponseWrapper::End($returned_data);
    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function update(TaskUpdateRequest $request, $id) : JsonResponse {
        $returned_data = ResponseWrapper::Start();

        try {
            $returned_data['results'] = $this->taskRepository->update($id, $request->validated());
        } catch (\Exception $exception){
            $returned_data['message'] = $exception->getMessage();
            $returned_data['code'] = 500;
        }

        return ResponseWrapper::End($returned_data);
    }


    /**
     * Destroy specific task
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id) : JsonResponse{
        $returned_data = ResponseWrapper::Start();

        if($this->taskRepository->delete($id)){
            $returned_data['results'] = true;
            $returned_data['message'] = 'Task deleted successfully';
        }


        return ResponseWrapper::End($returned_data);
    }


    /**
     * Assign task to user
     * @param AssignTaskRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function assign(AssignTaskRequest $request, $id): JsonResponse
    {
        $returned_data = ResponseWrapper::Start();
        try {
            $userId = $request->input('user_id');
            $this->taskRepository->assign($id, $userId);

            $returned_data['results'] = true;
            $returned_data['message'] = 'Task assigned successfully.';
            $returned_data['code'] = 200;

        } catch (\Exception $e) {
            $returned_data['message'] = $e->getMessage();
            $returned_data['code'] = 500;
        }

        return ResponseWrapper::End($returned_data);
    }
}
