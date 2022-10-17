<?php

namespace Tests\Feature\Requests\Api;

use App\Http\Requests\Api\UpdateGoalRequest;
use App\Models\{Skill, User};
use Illuminate\Support\Facades\Validator;
use Tests\Feature\BaseFeature;

class UpdateGoalRequestTest extends BaseFeature
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
        $this->skill = Skill::create(['title' => 'new test skill']);
    }

    /**
     * Проверим request с набором валидных данных
     * @test
     * @dataProvider validDataProvider
     */
    public function checkValidDataCodeRequest(array $data): void
    {
        $request = new UpdateGoalRequest(['goal' => $this->goal]);
        $request->setUserResolver(fn($user) => $this->user);
        $request->setMethod('PUT');
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
        $request = new UpdateGoalRequest(['goal' => $this->goal]);
        $request->setUserResolver(fn($user) => $this->user);
        $request->setMethod('PUT');
        $this->assertTrue($request->authorize());

        if ($this->dataName() != 'чужая цель') {
            if ($this->dataName() != 'некорректный skill_id') {
                $data['skill_id'] = $this->skill->id;
            }
            $validator = Validator::make($data, $request->rules());
            $this->assertFalse($validator->passes());
        } else {
            $request->setUserResolver(fn($user) => $this->createMock(User::class));
            $this->assertFalse($request->authorize());
        }
    }

    /**
     * Набор валидных данных для тестирования
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            'обновление title' =>
                [[
                    'title'       => 'new title',
                ]],
            'обновление skill_id' =>
                [[
                    'skill_id'    => 1,
                ]],
            'обновление status' =>
                [[
                    'status'      => 'complete',
                ]],
            'обновление start_at' =>
                [[
                    'start_at'    => '17.01.2022',
                ]],
            'обновление deadline_at' =>
                [[
                    'deadline_at' => '17.01.2023',
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
            'некорректный title' =>
                [[
                    'title' => 123,
                ]],
            'некорректный skill_id' =>
                [[
                    'skill_id' => 1,
                ]],
            'некорректный status' =>
                [[
                    'status' => 'Нет такого варианта в enum',
                ]],
            'некорректный start_at' =>
                [[
                    'start_at' => 'это не дата',
                ]],
            'некорректный deadline_at' =>
                [[
                    'deadline_at' => 'это не дата',
                ]],
            'чужая цель' =>
                [[
                    //
                ]],
        ];
    }
}
