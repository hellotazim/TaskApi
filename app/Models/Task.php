<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'status',
        'priority',
    ];


    /**
     * Task Status Enums
     */
    const STATUSES = ['Todo', 'In Progress', 'Done'];


    /**
     * Task Priority Enums
     */
    const PRIORITIES = ['Low', 'Medium', 'High'];


    /**
     * Get assigned users
     * @return BelongsToMany
     */
    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_users')->withTimestamps();
    }
}
