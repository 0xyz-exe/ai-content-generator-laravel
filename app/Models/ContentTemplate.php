<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'icon',
        'system_prompt',
        'cost_credits',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'cost_credits' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
