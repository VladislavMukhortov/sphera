<?php

namespace Tests\Feature\Requests\Api;

use App\Http\Requests\Api\StoreTaskRequest;
use Illuminate\Support\Facades\Validator;
use Tests\Feature\BaseFeature;

class StoreTaskRequestTest extends BaseFeature
{
    /**
     * Проверим request с набором валидных данных
     * @test
     * @dataProvider validDataProvider
     */
    public function checkValidDataCodeRequest(array $data): void
    {
        $request = new StoreTaskRequest(['goal' => $this->goal]);
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
        $request = new StoreTaskRequest(['goal' => $this->goal]);
        $request->setMethod('POST');
        $this->assertTrue($request->authorize());

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
            'корректные данные' => [[
                    'title' => 'test title',
                    'price' => 1000,
                    'schedule' => '* * * * *',
                    'is_completed' => false,
                    'start_at' => '12.01.2022',
                    'deadline_at' => '12.02.2022'
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
            'title = 123 (int)' => [[
                    'title' => 123,
                    'price' => 1000,
                    'schedule' => '* * * * *',
                    'is_completed' => false,
                    'start_at' => '12.01.2022',
                    'deadline_at' => '12.02.2022'
                ]],
            'price = string' => [[
                    'title' => 'test title',
                    'price' => 'string',
                    'schedule' => '* * * * *',
                    'is_completed' => false,
                    'start_at' => '12.01.2022',
                    'deadline_at' => '12.02.2022'
                ]],
            'schedule = int' => [[
                    'title' => 'test title',
                    'price' => 1000,
                    'schedule' => 123,
                    'is_completed' => false,
                    'start_at' => '12.01.2022',
                    'deadline_at' => '12.02.2022'
                ]],
            'is_completed = string' => [[
                    'title' => 'test title',
                    'price' => 1000,
                    'schedule' => '* * * * *',
                    'is_completed' => 'string',
                    'start_at' => '12.01.2022',
                    'deadline_at' => '12.02.2022'
                ]],
            'start_at = int' => [[
                    'title' => 'test title',
                    'price' => 1000,
                    'schedule' => '* * * * *',
                    'is_completed' => false,
                    'start_at' => 123,
                    'deadline_at' => '12.02.2022'
                ]],
            'deadline_at = int' => [[
                    'title' => 'test title',
                    'price' => 1000,
                    'schedule' => '* * * * *',
                    'is_completed' => false,
                    'start_at' => '12.01.2022',
                    'deadline_at' => 123
                ]],
            'без title' => [[
                    'price' => 1000,
                    'schedule' => '* * * * *',
                    'is_completed' => false,
                    'start_at' => '12.01.2022',
                    'deadline_at' => '12.02.2022'
                ]],
            'без price' => [[
                    'title' => 'test title',
                    'schedule' => '* * * * *',
                    'is_completed' => false,
                    'start_at' => '12.01.2022',
                    'deadline_at' => '12.02.2022'
                ]],
            'без schedule' => [[
                    'title' => 'test title',
                    'price' => 1000,
                    'is_completed' => false,
                    'start_at' => '12.01.2022',
                    'deadline_at' => '12.02.2022'
                ]],
            'без is_completed' => [[
                    'title' => 'test title',
                    'price' => 1000,
                    'schedule' => '* * * * *',
                    'start_at' => '12.01.2022',
                    'deadline_at' => '12.02.2022'
                ]],
        ];
    }
}
