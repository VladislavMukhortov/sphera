<?php

namespace Tests\Feature\Requests\Api;

use App\Http\Requests\Api\StoreUserFirebaseTokenRequest;
use Illuminate\Support\Facades\Validator;
use Tests\Feature\BaseFeature;

class StoreUserFirebaseTokenRequestTest extends BaseFeature
{
    /**
     * Проверим request с набором валидных данных
     * @test
     * @dataProvider validDataProvider
     */
    public function checkValidDataCodeRequest(array $data): void
    {
        $request = new StoreUserFirebaseTokenRequest();
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
        $request = new StoreUserFirebaseTokenRequest();
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
                    'token' => '12312asdasdasd123123@@@11',
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
            'token = int' => [[
                    'token' => 123,
                ]],
            'без token' => [[
                    'token' => ''
                ]],
        ];
    }
}
