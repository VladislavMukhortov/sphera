<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillLocale extends Model
{
    use HasFactory;

    protected $table = 'skill_locales';

    protected $fillable = [
        'lang',
        'title',
    ];

    /**
     * Возвращает родительский скил
     * @return BelongsTo
     */
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }
}
