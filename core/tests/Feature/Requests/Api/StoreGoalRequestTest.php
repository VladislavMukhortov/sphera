<?php

namespace Tests\Feature\Requests\Api;

use App\Http\Requests\Api\StoreGoalRequest;
use App\Models\Skill;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreGoalRequestTest extends TestCase
{
    /**
     * Навык
     *
     * @var Skill
     */
    private Skill $skill;

    /**
     * Создание навыка для теста
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->skill = Skill::create(['title' => 'test skill'])->first();
    }

    /**
     * Проверим request с набором валидных данных
     * @test
     * @dataProvider validDataProvider
     */
    public function checkValidDataCodeRequest(array $data): void
    {
        $request = new StoreGoalRequest();
        $this->assertTrue($request->authorize());

        $data['skill_id'] = $this->skill->id;

        $validator = Validator::make($data, $request->rules());
        $this->assertTrue($validator->passes());
    }

    /**
     * Проверим request с набором битых данных
     * @test
     * @dataProvider invalidDataProvider
     */
    public function checkInvalidDataCodeRequest(array $data): void
    {
        $request = new StoreGoalRequest();
        $this->assertTrue($request->authorize());

        if ($this->dataName() != 'без skill_id') {
            $data['skill_id'] = $this->skill->id;
        }

        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
    }

    /**
     * Набор валидных данных для тестирования
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            'цель-список' =>
                [[
                    'title' => 'Test goal',
                    'type' => 'list',
                    'skill_id' => '1',
                    'start_at' => '11.01.2022',
                    'deadline_at' => '21.01.2022',
                ]],
            'цель-круг' =>
                [[
                    'title' => 'Test goal',
                    'type' => 'repeat',
                    'skill_id' => '1',
                    'start_at' => '11.01.2022',
                    'deadline_at' => '21.01.2022',
                    'action_button' => 'Пуск',
                    'unit' => 'count',
                    'target_count' => '10',
                ]],
        ];
    }

    /**
     * Набор битых данных для тестирования
     * @return array
     */
    public function invalidDataProvider(): array
    {
        return [
            'без title' =>
                [[
                    'type' => 'list',
                    'skill_id' => '1',
                    'start_at' => '11.01.2022',
                    'deadline_at' => '21.01.2022',
                ]],
            'без skill_id' =>
                [[
                    'title' => 'Test goal',
                    'type' => 'list',
                    'start_at' => '11.01.2022',
                    'deadline_at' => '21.01.2022',
                ]],
            'без type' =>
                [[
                    'title' => 'Test goal',
                    'skill_id' => '1',
                    'start_at' => '11.01.2022',
                    'deadline_at' => '21.01.2022',
                ]],
            'некорректный type' =>
                [[
                    'title' => 123,
                    'type' => 'type',
                    'skill_id' => '1',
                    'start_at' => '11.01.2022',
                    'deadline_at' => '21.01.2022',
                ]],
            'некорректный title' =>
                [[
                    'title' => 123,
                    'type' => 'list',
                    'skill_id' => '1',
                    'start_at' => '11.01.2022',
                    'deadline_at' => '21.01.2022',
                ]],
            'некорректный start_at' =>
                [[
                    'title' => 'Test goal',
                    'type' => 'list',
                    'skill_id' => '1',
                    'start_at' => 'asd',
                    'deadline_at' => '21.01.2022',
                ]],
            'некорректный deadline_at' =>
                [[
                    'title' => 'Test goal',
                    'type' => 'list',
                    'skill_id' => '1',
                    'start_at' => '11.01.2022',
                    'deadline_at' => 'asd',
                ]],
            'цель-круг без action_button,unit,target_count' =>
                [[
                    'title' => 'Test goal',
                    'type' => 'repeat',
                    'skill_id' => '1',
                    'start_at' => '11.01.2022',
                    'deadline_at' => '21.01.2022',
                ]],
            'цель-круг некорректный action_button' =>
                [[
                    'title' => 'Test goal',
                    'type' => 'repeat',
                    'skill_id' => '1',
                    'start_at' => '11.01.2022',
                    'deadline_at' => '21.01.2022',
                    'action_button' => 'Здесь слишком много символов для кнопки!',
                    'unit' => 'count',
                    'target_count' => '10',
                ]],
            'цель-круг некорректный unit' =>
                [[
                    'title' => 'Test goal',
                    'type' => 'repeat',
                    'skill_id' => '1',
                    'start_at' => '11.01.2022',
                    'deadline_at' => '21.01.2022',
                    'action_button' => 'Кнопка',
                    'unit' => 'unit',
                    'target_count' => '10',
                ]],
            'цель-круг некорректный target_count' =>
                [[
                    'title' => 'Test goal',
                    'type' => 'repeat',
                    'skill_id' => '1',
                    'start_at' => '11.01.2022',
                    'deadline_at' => '21.01.2022',
                    'action_button' => 'Кнопка',
                    'unit' => 'unit',
                    'target_count' => 'Здесь должны быть цифры',
                ]],
        ];
    }
}
