<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $application_id
 * @property int|null $changed_by
 * @property ApplicationStatus|null $from_status
 * @property ApplicationStatus $to_status
 * @property string|null $note
 */
class ApplicationStatusChange extends Model
{
    protected $fillable = ['application_id', 'changed_by', 'from_status', 'to_status', 'note'];

    protected function casts(): array
    {
        return [
            'from_status' => ApplicationStatus::class,
            'to_status' => ApplicationStatus::class,
        ];
    }

    /** @return BelongsTo<Application, $this> */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /** @return BelongsTo<User, $this> */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
