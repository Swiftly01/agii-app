<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
       protected $fillable = [
        'marketer_id',
        'assigned_by',
        'title',
        'description',
        'notes',
        'priority',
        'status',
        'progress',
        'deadline',
        'assignby_name',
        'report_to',
        'target_leads',    
        'attachment',// Add this
        'target_conversion',    // Add this
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deadline' => 'datetime',
        'progress' => 'integer',
        'target_leads' => 'integer',        // Add this
        'target_conversion' => 'float',      // Add this
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deadline',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the marketer assigned to this task.
     */
    public function marketer()
    {
        return $this->belongsTo(User::class, 'marketer_id');
    }

    /**
     * Get the user who assigned this task.
     */
    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Scope for overdue tasks.
     */
    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', now())
            ->where('status', '!=', 'completed');
    }

    /**
     * Scope for pending tasks.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for in-progress tasks.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope for completed tasks.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for tasks by priority.
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for tasks assigned to specific marketer.
     */
    public function scopeAssignedTo($query, $marketerId)
    {
        return $query->where('marketer_id', $marketerId);
    }

    /**
     * Check if task is overdue.
     */
    public function isOverdue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->deadline < now() && $this->status !== 'completed'
        );
    }

    /**
     * Get the formatted priority with color.
     */
    public function priorityColor(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match($this->priority) {
                    'high' => 'danger',
                    'medium' => 'warning',
                    'low' => 'success',
                    default => 'secondary'
                };
            }
        );
    }

    /**
     * Get the formatted status with color.
     */
    public function statusColor(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match($this->status) {
                    'completed' => 'success',
                    'in_progress' => 'primary',
                    'pending' => $this->is_overdue ? 'danger' : 'warning',
                    default => 'secondary'
                };
            }
        );
    }

    /**
     * Add a status update note.
     */
    public function addStatusNote(string $note): void
    {
        $timestamp = now()->format('Y-m-d H:i');
        $statusNote = "Status Update [{$timestamp}]: {$note}";
        
        $this->update([
            'notes' => $this->notes 
                ? $this->notes . "\n\n" . $statusNote
                : $statusNote
        ]);
    }

    /**
     * Update task progress with validation.
     */
    public function updateProgress(int $progress): bool
    {
        if ($progress < 0 || $progress > 100) {
            return false;
        }

        $this->update(['progress' => $progress]);
        
        // Auto-update status based on progress
        if ($progress == 100) {
            $this->update(['status' => 'completed']);
        } elseif ($progress > 0 && $this->status == 'pending') {
            $this->update(['status' => 'in_progress']);
        }

        return true;
    }

    /**
     * Get the days remaining until deadline.
     */
    public function daysRemaining(): Attribute
    {
        return Attribute::make(
            get: fn () => now()->diffInDays($this->deadline, false)
        );
    }

    /**
     * Check if task can be marked as completed.
     */
    public function canBeCompleted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status !== 'completed'
        );
    }
}