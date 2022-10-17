<?php

namespace Tests\Feature\Requests\Api;

use App\Http\Requests\Api\UpdateGoalRepeatRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Tests\Feature\BaseFeature;

class UpdateGoalRepeatRequestTest extends BaseFeature
{
    /**
     * Шаг круговой цели
     *
     * @var Model
     */
    private Model $repeat;

    /**
     * Подготовка класса
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->repeat = $this->goal->repeats()->create(['count' => 5]);
    }

    /**
     * Проверим request с набором валидных данных
     * Базовые модели создаются в родительском классе
     * @test
     * @dataProvider validDataProvider
     */
    public function checkValidDataCodeRequest(array $data): void
    {
        $request = new UpdateGoalRepeatRequest(['goal' => $this->goal, 'repeat' => $this->repeat]);
        $request->setUserResolver(fn($user) => $this->user);
        $request->setMethod('POST');
        $this->assertTrue($request->authorize());

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
        $request = new UpdateGoalRepeatRequest(['goal' => $this->goal, 'repeat' => $this->repeat]);
        $request->setUserResolver(fn($user) => $this->user);
        $request->setMethod('POST');

        if ($this->dataName() != 'чужая цель') {
            $this->assertTrue($request->authorize());
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
            'обновление 5->7 (лимит 10)' => [[
                    'count' => 7,
                ]],
            'обновление 5->10 (лимит 10)' => [[
                    'count' => 10,
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
            'обновление 5->11 (лимит 10)' => [[
                    'count' => 11,
                ]],
            'count = string' => [[
                    'count' => 'asd',
                ]],
            'без count' => [[
                    'count' => '',
                ]],
            'чужая цель' => [[
                   //
                ]],
        ];
    }
}
