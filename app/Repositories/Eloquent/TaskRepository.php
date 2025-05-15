<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class TaskRepository implements TaskRepositoryInterface
{
    public function all($filters)
    {
        $query = Task::query();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['due_date'])) {
            $query->whereDate('due_date', $filters['due_date']);
        }

        if (!empty($filters['sort'])) {
            $sortField = ltrim($filters['sort'], '-');
            $sortDirection = starts_with($filters['sort'], '-') ? 'desc' : 'asc';
            $query->orderBy($sortField, $sortDirection);
        }

        return $query->paginate(10);
    }

    public function find($id)
    {
        return Task::with('assignedUsers')->findOrFail($id);
    }

    public function create(array $data)
    {
        $data['user_id'] = Auth::id();
        return Task::create($data);
    }

    public function update($id, array $data)
    {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task;
    }

    public function delete($id)
    {
        return Task::where('id', '=', $id)->where('user_id', '=', Auth::user()->id)->delete();
    }

    public function assign($taskId, $userId)
    {
        $task = Task::findOrFail($taskId);
        $task->assignedUsers()->syncWithoutDetaching([$userId]);
        return $task->load('assignedUsers');
    }
}
