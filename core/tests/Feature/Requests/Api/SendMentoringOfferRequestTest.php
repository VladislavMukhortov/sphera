<?php

namespace Tests\Feature\Requests\Api;

use App\Http\Requests\Api\SendMentoringOfferRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Tests\Feature\BaseFeature;

class SendMentoringOfferRequestTest extends BaseFeature
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
        $request = new SendMentoringOfferRequest(['goal' => $this->goal]);
        $request->setMethod('POST');

        if ($data['make_user'] == 'mentor') {
            $request->setUserResolver(fn($user) => $this->user);
            $data['user_uuid'] = $this->another_user->uuid;
        } else {
            $request->setUserResolver(fn($user) => $this->another_user);
            $request->merge(['user_uuid' => $this->user->uuid]);
            $data['user_uuid'] = $this->user->uuid;
        }
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
        $request = new SendMentoringOfferRequest(['goal' => $this->goal]);
        $request->setUserResolver(fn($user) => $this->user);
        $request->setMethod('POST');

        if ($this->dataName() != 'чужая цель') {
            if (!in_array($this->dataName(), ['некорректный user_uuid', 'отсутствует user_uuid'])) {
                $data['user_uuid'] = $this->another_user->uuid;
            }
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
            'запрашиваем менторство по своей цели' => [[
                    'user_uuid' => '*some_uuid*',
                    'make_user' => 'mentor',
                ]],
            'предлагаем менторство по чужой цели' => [[
                    'user_uuid' => '*some_uuid*',
                    'make_user' => 'student',
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
            'некорректный вариант взаимодействия' => [[
                'user_uuid' => '*some_uuid*',
                'make_user' => 'teach_me',
            ]],
            'некорректный user_uuid' => [[
                'user_uuid' => '*some_uuid*',
                'make_user' => 'mentor',
            ]],
            'отсутствует make_user' => [[
                'user_uuid' => '*some_uuid*',
                'make_user' => '',
            ]],
            'отсутствует user_uuid' => [[
                'user_uuid' => '',
                'make_user' => 'mentor',
            ]],
            'чужая цель' => [[
                //
            ]],
        ];
    }
}
