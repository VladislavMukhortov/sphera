<?php

namespace Tests\Feature\Requests\Api;

use App\Http\Requests\Api\MentoringDecisionRequest;
use App\Models\{User, UserNotification};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Tests\Feature\BaseFeature;

class UpdateMentoringRequestTest extends BaseFeature
{
    /**
     * Сторонний пользователь
     * @var Model
     */
    private Model $another_user;

    /**
     * Уведомление
     * @var UserNotification
     */
    private UserNotification $notification;

    /**
     * Создание стороннего пользователя и уведомления
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->another_user = User::factory()->create();
        $this->notification = $this->another_user->notifications()->create([
            'essence_id' => $this->goal->id,
            'initiator_id' => $this->user->id,
            'type' => 'become_student_declined',
            'status' => 'new'
        ]);
    }

    /**
     * Проверим request с набором валидных данных
     * Базовые модели создаются в родительском классе
     * @test
     * @dataProvider validDataProvider
     */
    public function checkValidDataCodeRequest(array $data): void
    {
        $request = new MentoringDecisionRequest(['goal' => $this->goal]);
        $request->setMethod('POST');

        if ($this->dataName() == 'запрос по своей цели') {
            $request->setUserResolver(fn($user) => $this->user);
            $data['user_uuid'] = $this->another_user->uuid;
        } else {
            $request->setUserResolver(fn($user) => $this->another_user);
            $request->merge(['user_uuid' => $this->user->uuid]);
            $data['user_uuid'] = $this->user->uuid;
        }
        $data['notification_id'] = $this->notification->id;
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
        $request = new MentoringDecisionRequest(['goal' => $this->goal]);
        $request->setUserResolver(fn($user) => $this->user);
        $request->setMethod('POST');

        if (in_array($this->dataName(), ['некорректный user_uuid', 'отсутствует user_uuid', 'notification_id = string'])) {
            $data['notification_id'] = $this->notification->id;
            $this->assertTrue($request->authorize());
        }
        if (in_array($this->dataName(), ['некорректный notification_id', 'отсутствует notification_id', 'user_uuid = integer'])) {
            $data['user_uuid'] = $this->another_user->uuid;
            $this->assertTrue($request->authorize());
        }
        if ($this->dataName() == 'у цели другой юзер') {
            $request->setUserResolver(fn($user) => $this->another_user);
            $this->assertFalse($request->authorize());
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
            'запрос по своей цели' => [[
                    'user_uuid' => '*some_uuid*',
                    'notification_id' => '*some_id*',
                ]],
            'запрос по чужой цели' => [[
                    'user_uuid' => '*some_uuid*',
                    'notification_id' => '*some_id*',
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
            'некорректный user_uuid' => [[
                'user_uuid' => '123-sss-aaa-222',
                'notification_id' => '*some_id*',
            ]],
            'некорректный notification_id' => [[
                'user_uuid' => '*some_uuid*',
                'notification_id' => 0,
            ]],
            'отсутствует user_uuid' => [[
                'user_uuid' => '',
                'notification_id' => '*some_id*',
            ]],
            'отсутствует notification_id' => [[
                'user_uuid' => '*some_uuid*',
                'notification_id' => '',
            ]],
            'user_uuid = integer' => [[
                'user_uuid' => 123,
                'notification_id' => '*some_id*',
            ]],
            'notification_id = string' => [[
                'user_uuid' => '*some_uuid*',
                'notification_id' => 'asd123',
            ]],
            'у цели другой юзер' => [[
                'user_uuid' => '*some_uuid*',
                'notification_id' => '*some_id*',
            ]],
        ];
    }
}
