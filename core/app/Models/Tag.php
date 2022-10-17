<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tags';

    /**
     * Id главного тега "Поиск ментора"
     *
     * @var int
     */
    public const SEARCH_MENTOR = 323;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'taggable',
    ];

    /**
     * Полиморфная связь с таблицей goals
     *
     * @return MorphToMany
     */
    public function goals(): MorphToMany
    {
        return $this->morphedByMany(Goal::class, 'taggable');
    }
}
