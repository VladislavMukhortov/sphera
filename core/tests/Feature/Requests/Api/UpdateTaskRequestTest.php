<?php

namespace Tests\Feature\Requests\Api;

use App\Http\Requests\Api\UpdateTaskRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tests\Feature\BaseFeature;

class UpdateTaskRequestTest extends BaseFeature
{
    /**
     * Проверим request с набором валидных данных
     * @test
     * @dataProvider validDataProvider
     */
    public function checkValidDataCodeRequest(array $data): void
    {
        $request = new UpdateTaskRequest(['goal' => $this->goal]);
        $request->setUserResolver(fn($user) => $this->user);
        $request->setMethod('PUT');
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
        $request = new UpdateTaskRequest(['goal' => $this->goal]);
        $request->setMethod('PUT');

        if ($this->dataName() != 'чужая цель') {
            $request->setUserResolver(fn($user) => $this->user);
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
            'обновление title' => [[
                    'title' => 'new title',
                ]],
            'обновление price' => [[
                    'price' => 2000,
                ]],
            'обновление schedule' => [[
                    'schedule' => 'new schedule',
                ]],
            'обновление is_completed' => [[
                    'is_completed' => true,
                ]],
            'обновление start_at' => [[
                    'start_at' => '12.10.2022',
                ]],
            'обновление deadline_at' => [[
                    'deadline_at' => '12.12.2022',
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
            'некорректный title' => [[
                'title' => 123,
            ]],
            'некорректный price' => [[
                'price' => 'high',
            ]],
            'некорректный schedule' => [[
                'schedule' => 500,
            ]],
            'некорректный is_completed' => [[
                'is_completed' => 'yes',
            ]],
            'некорректный start_at' => [[
                'start_at' => 123,
            ]],
            'некорректный deadline_at' => [[
                'deadline_at' => 123,
            ]],
            'чужая цель' => [[
                //
            ]],
        ];
    }
}
