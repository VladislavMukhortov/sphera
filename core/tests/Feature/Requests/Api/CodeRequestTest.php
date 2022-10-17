<?php

namespace Tests\Feature\Requests\Api;

use App\Http\Requests\Api\CodeRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

use function Livewire\str;

class CodeRequestTest extends TestCase
{
    /**
     * Проверим request с набором валидных данных
     * @test
     * @dataProvider validDataProvider
     */
    public function checkValidDataCodeRequest(array $data): void
    {
        $request = new CodeRequest();
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
        $request = new CodeRequest();
        $this->assertTrue($request->authorize());

        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
    }

    /**
     * Набор валидных данных для тестирования форм request
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            '79116015331 (str)' => [[
                    'login' => '79116015331',
                ]],
            '+79116015332 (str)' => [[
                    'login' => '+79116015332',
                ]],
            '89116015333 (str)' => [[
                    'login' => '89116015333',
                ]],
            'example@domain.com' => [[
                    'login' => 'example@domain.com',
                ]],
            'example@gmail.com' => [[
                    'login' => 'example@gmail.com',
                ]],
        ];
    }

    /**
     * Набор битых данных для тестирования форм request
     * @return array
     */
    public function invalidDataProvider(): array
    {
        return [
            'example' => [[
                    'login' => 'example',
                ]],
            'example@' => [[
                    'login' => 'example@',
                ]],
            '@example' => [[
                    'login' => '@example',
                ]],
            'domain.com' => [[
                    'login' => 'domain.com',
                ]],
            '.com' => [[
                    'login' => '.com',
                ]],
            '@' => [[
                    'login' => '@',
                ]],
            '*empty*' => [[
                    'login' => '',
                ]],
            'null' => [[
                    'login' => null,
                ]],
            '1234567 (int)' => [[
                    'login' => 1234567,
                ]],
            '1234567891111 (int)' => [[
                    'login' => 1234567891111,
                ]],
            'too_many_symbols_asdasdasda...' => [[
                    'login' => 'too_many_symbols_asdasdasdasdsadasdasdasdasdasasdasdasdasdas@domain.com',
                ]],
            '1234567 (str)' => [[
                    'login' => '1234567',
                ]],
            '1234567891111 (str)' => [[
                    'login' => '1234567891111',
                ]],
            '[]' => [[
                    'login' => [],
                ]],
            'true' => [[
                    'login' => true,
                ]],
        ];
    }
}
