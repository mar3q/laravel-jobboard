<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Enums\ApplicationStatus;
use Database\Factories\ApplicationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $job_id
 * @property int $user_id
 * @property int|null $cv_id
 * @property ApplicationStatus $status
 * @property string|null $cover_letter
 * @property string $contact_email
 * @property Carbon|null $reviewed_at
 */
class Application extends Model
{
    /** @use HasFactory<ApplicationFactory> */
    use HasFactory;

    protected $fillable = [
        'job_id', 'user_id', 'cv_id', 'status',
        'cover_letter', 'contact_email', 'contact_phone',
        'reviewed_at', 'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => ApplicationStatus::class,
            'reviewed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Job, $this> */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Cv, $this> */
    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    /** @return BelongsTo<User, $this> */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /** @return HasMany<ApplicationStatusChange, $this> */
    public function statusChanges(): HasMany
    {
        return $this->hasMany(ApplicationStatusChange::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', ApplicationStatus::Pending->value);
    }

    public function scopeForJob(Builder $query, Job $job): Builder
    {
        return $query->where('job_id', $job->id);
    }
}
