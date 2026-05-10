<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $website
 * @property string|null $logo_path
 * @property string|null $description
 * @property string|null $city
 * @property string $country
 * @property bool $is_verified
 */
class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'website', 'logo_path', 'nip', 'size',
        'industry', 'description', 'city', 'country', 'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Company $company): void {
            if (empty($company->slug)) {
                $company->slug = Str::slug($company->name).'-'.Str::random(6);
            }
        });
    }

    /** @return HasMany<Job, $this> */
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    /** @return BelongsToMany<User, $this> */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }

    /** @return MorphToMany<Tag, $this> */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('is_verified', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
