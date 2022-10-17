<?php

namespace Tests\Feature\Requests\Api;

use App\Http\Requests\Api\RemoveMentoringRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Tests\Feature\BaseFeature;

class RemoveMentoringRequestTest extends BaseFeature
{
    /**
     * Сторонний пользователь
     * @var Model
     */
    private Model $another_user;

    /**
     * Создание стороннего пользователя
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->another_user = User::factory()->create();
    }

    /**
     * Проверим request с набором валидных данных
     * Базовые модели создаются в родительском классе
     * @test
     * @dataProvider validDataProvider
     */
    public function checkValidDataCodeRequest(array $data): void
    {
        $request = new RemoveMentoringRequest(['goal' => $this->goal]);
        $request->setMethod('DELETE');

        if ($this->dataName() == 'отказываемся от ментора') {
            $request->setUserResolver(fn($user) => $this->user);
            $this->assertTrue($request->authorize());
        } else {
            $request->setUserResolver(fn($user) => $this->another_user);
            $request->merge(['user_uuid' => $this->user->uuid]);
            $this->goal->update(['mentor_id' => $this->another_user->id]);
            $this->assertTrue($request->authorize());

            $data['user_uuid'] = $this->another_user->uuid;
            $validator = Validator::make($data, $request->rules());
            $this->assertTrue($validator->passes());
        }
    }

    /**
     * Проверим request с набором битых данных
     * @test
     * @dataProvider invalidDataProvider
     */
    public function checkInvalidDataCodeRequest(array $data): void
    {
        $request = new RemoveMentoringRequest(['goal' => $this->goal]);
        $request->setMethod('DELETE');
        $request->setUserResolver(fn($user) => $this->another_user);

        if ($this->dataName() == 'убрать ментора не своей задачи') {
            $this->assertFalse($request->authorize());
        } elseif ($this->dataName() == 'отказываемся от чужого студента') {
            $request->merge(['user_uuid' => $this->user->uuid]);
            $this->assertFalse($request->authorize());

            $validator = Validator::make($data, $request->rules());
            $this->assertFalse($validator->passes());
        } else {
            $validator = Validator::make($data, $request->rules());
            $this->assertFalse($validator->passes());
        }
    }

    /**
     * Набор валидных данных для тестирования
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            'отказываемся от ментора' =>
                [[
                    //
                ]],
            'отказываемся от студента' =>
                [[
                    'user_uuid' => '*some_uuid*'
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
            'убрать ментора не своей задачи' =>
                [[
                    //
                ]],
            'отказываемся от чужого студента' =>
                [[
                    'user_uuid' => '*some_uuid*'
                ]],
            'некорректный uuid' =>
                [[
                    'user_uuid' => '*some_uuid*'
                ]],
        ];
    }
}
