<?php

namespace Tests\Feature\Requests\Api;

use App\Http\Requests\Api\StoreGoalRepeatRequest;
use Illuminate\Support\Facades\Validator;
use Tests\Feature\BaseFeature;

class StoreGoalRepeatRequestTest extends BaseFeature
{
    /**
     * Проверим request с набором валидных данных
     * Базовые модели создаются в родительском классе
     * @test
     * @dataProvider validDataProvider
     */
    public function checkValidDataCodeRequest(array $data): void
    {
        $request = new StoreGoalRepeatRequest(['goal' => $this->goal]);
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
        $request = new StoreGoalRepeatRequest(['goal' => $this->goal]);
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
            '+1 круг/действие (лимит 10)' => [[
                    'count' => 1,
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
            '+11 кругов/действий (лимит 10)' => [[
                    'count' => 11,
                ]],
        ];
    }
}
