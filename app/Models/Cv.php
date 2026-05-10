<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CvFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $label
 * @property string $original_filename
 * @property string $path
 * @property string $mime_type
 * @property int $size_bytes
 * @property bool $is_default
 */
class Cv extends Model
{
    /** @use HasFactory<CvFactory> */
    use HasFactory;

    protected $table = 'cvs';

    protected $fillable = [
        'user_id', 'label', 'original_filename', 'path',
        'mime_type', 'size_bytes', 'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'size_bytes' => 'integer',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
