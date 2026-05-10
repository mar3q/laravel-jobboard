<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Casts\SalaryRangeCast;
use App\Support\Enums\ContractType;
use App\Support\Enums\JobStatus;
use App\Support\Enums\Seniority;
use Database\Factories\JobFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $company_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property JobStatus $status
 * @property Seniority $seniority
 * @property ContractType $contract_type
 * @property bool $remote
 * @property bool $hybrid
 * @property string|null $location_city
 * @property string $location_country
 * @property int $views_count
 * @property int $applications_count
 * @property Carbon|null $published_at
 * @property Carbon|null $expires_at
 * @property-read \App\Support\ValueObjects\SalaryRange $salary
 */
class Job extends Model
{
    /** @use HasFactory<JobFactory> */
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status', 'salary_min', 'salary_max', 'published_at', 'expires_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'company_id', 'job_category_id', 'created_by',
        'title', 'slug', 'description', 'requirements', 'benefits',
        'seniority', 'contract_type', 'status',
        'salary_min', 'salary_max', 'salary_currency',
        'location_city', 'location_country', 'remote', 'hybrid',
        'published_at', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => JobStatus::class,
            'seniority' => Seniority::class,
            'contract_type' => ContractType::class,
            'remote' => 'boolean',
            'hybrid' => 'boolean',
            'salary' => SalaryRangeCast::class,
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
            'views_count' => 'integer',
            'applications_count' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Job $job): void {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title).'-'.Str::random(6);
            }
            if ($job->status === JobStatus::Published && $job->published_at === null) {
                $job->published_at = now();
            }
        });
    }

    /** @return BelongsTo<Company, $this> */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /** @return BelongsTo<JobCategory, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** @return HasMany<Application, $this> */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /** @return MorphToMany<Tag, $this> */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /** @return BelongsToMany<User, $this> */
    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_jobs')->withTimestamps();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', JobStatus::Published->value)
            ->where(function (Builder $q): void {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function scopeRemote(Builder $query): Builder
    {
        return $query->where('remote', true);
    }

    public function scopeForLocation(Builder $query, string $city): Builder
    {
        return $query->where('location_city', $city);
    }

    public function scopeWithSeniority(Builder $query, Seniority $seniority): Builder
    {
        return $query->where('seniority', $seniority->value);
    }

    public function scopeWithContractType(Builder $query, ContractType $type): Builder
    {
        return $query->where('contract_type', $type->value);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        if ($query->getConnection()->getDriverName() === 'mysql') {
            return $query->whereFullText(['title', 'description'], $term);
        }

        return $query->where(function (Builder $q) use ($term): void {
            $q->where('title', 'like', '%'.$term.'%')
              ->orWhere('description', 'like', '%'.$term.'%');
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
