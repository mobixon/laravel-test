<?php

namespace App\Models;

use App\Exceptions\TaskException;
use App\Http\Filters\QueryFillterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Task
 *
 * @property int $id
 * @property string $status
 * @property string $title
 * @property string $description
 * @property int $priority
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Task extends Model
{
    use HasFactory;

    public const STATUS_TODO = 'todo';
    public const STATUS_DONE = 'done';

    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'parent_id',
    ];

    public function __construct(array $attributes = [])
    {
        $this->status = self::STATUS_TODO;
        parent::__construct($attributes);
    }

    public function parent(): HasOne
    {
        return $this->hasOne(Task::class, 'id', 'parent_id');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function isDone(): bool
    {
        return $this->status === self::STATUS_DONE;
    }

    public function setStatusAttribute(string $value)
    {
        if ($this->isDone()) {
            throw new TaskException('Can\'t change status. Task is already done');
        }

        if ($value === self::STATUS_DONE) {
            $notDoneTasks = $this->getNotDoneSubtasks();
            if (!empty($notDoneTasks)) {
                throw new TaskException('Can\'t move task to done. It has not done subtasks');
            }
        }

        $this->attributes['status'] = $value;
    }

    public function getNotDoneSubtasks(): array
    {
        $notDoneTasks = [];

        foreach ($this->subtasks as $subtask) {
            if (!$subtask->isDone()) {
                $notDoneTasks[] = $subtask;
            }
        }

        return $notDoneTasks;
    }

    public function getAllDoneSubtasks($task, &$doneTasks)
    {
        foreach ($task->subtasks as $subtask) {
            if ($subtask->status === self::STATUS_DONE) {
                $doneTasks[] = $subtask;
            } else {
                $this->getAllDoneSubtasks($subtask, $doneTasks);
            }
        }
    }

    public static function filter(QueryFillterInterface $filter): Builder
    {
        return $filter->apply(static::query());
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Task $task) {
            $parent = $task->parent;
            if ($parent && $parent->isDone()) {
                throw new TaskException('Can\'t create subtask. Parent task is done');
            }
        });

        static::updating(function (Task $task) {
            if ($task->isDone()) {
                throw new TaskException('Can\'t update task. Task is done');
            }
        });

        static::deleting(function (Task $task) {
            if ($task->isDone()) {
                throw new TaskException('Cannot delete task in status "done".');
            }

            $doneTasks = [];
            $task->getAllDoneSubtasks($task, $doneTasks);

            if (count($doneTasks)) {
                throw new TaskException('Cannot delete task it has subtasks in "done" status.');
            }
        });
    }
}
