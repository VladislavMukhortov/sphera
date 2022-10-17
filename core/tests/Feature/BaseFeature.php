<?php

namespace Tests\Feature;

use App\Models\{Goal, GoalOption, User};
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BaseFeature extends TestCase
{
    use DatabaseTransactions;

    /**
     * Тестовая модель пользователя
     *
     * @var User
     */
    protected User $user;

    /**
     * Тестовая модель цели
     *
     * @var Goal
     */
    protected Goal $goal;

    /**
     * Тестовая модель опции цели (для круговой цели)
     *
     * @var GoalOption
     */
    protected GoalOption $option;

    /**
     * Создаем необходимые базовые модели
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create()->first();
        $this->goal = Goal::factory()->create(['user_id' => $this->user->id, 'type' => 'repeat'])->first();
        $this->option = $this->goal->option()->create(['action_button' => 'Пуск', 'target_count' => 10, 'unit' => 'lap'])->first();
    }
}
