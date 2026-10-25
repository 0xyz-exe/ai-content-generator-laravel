<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content_template_id',
        'title',
        'prompt',
        'tone',
        'model',
        'generated_content',
        'tokens_used',
        'credits_used',
        'is_favorite',
    ];

    protected function casts(): array
    {
        return [
            'tokens_used' => 'integer',
            'credits_used' => 'integer',
            'is_favorite' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ContentTemplate::class, 'content_template_id');
    }
}
