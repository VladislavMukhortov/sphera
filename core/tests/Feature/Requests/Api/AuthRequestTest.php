<?php

namespace Tests\Feature\Requests\Api;

use App\Http\Requests\Api\AuthRequest;
use App\Models\TempCode;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class AuthRequestTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Проверим request с набором валидных данных
     * @test
     * @dataProvider validDataProvider
     */
    public function checkValidDataAuthRequest(array $data): void
    {
        TempCode::create($data);
        $request = new AuthRequest();
        $this->assertTrue($request->authorize());

        $validator = Validator::make($data, $request->rules());
        $this->assertTrue($validator->passes());
    }

    /**
     * Проверим request с набором битых данных
     * @test
     * @dataProvider invalidDataProvider
     */
    public function checkInvalidDataAuthRequest(array $data): void
    {
        // если в data есть check_database, то будем создавать запись в базе
        if (Arr::get($data, 'check_database', false) === true) {
            // Уберем ненужные данные в request
            unset($data['check_database']);
            // Создадим корректную запись в базу
            TempCode::create([
                'login' => $data['login'],
                'code' => TempCode::generateCode(),
            ]);
        }
        $request = new AuthRequest();
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
            'логин = email' => [[
                    'login' => Factory::create()->freeEmail(),
                    'code' => TempCode::generateCode(),
                ]],
            'логин = phone' => [[
                    'login' => '79116015332',
                    'code' => TempCode::generateCode(),
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
            // Валидный код. Невалидный логин
            'логин = example' => [[
                    'login' => 'example',
                    'code' => TempCode::generateCode(),
                ]],
            'логин = example@' => [[
                    'login' => 'example@',
                    'code' => TempCode::generateCode(),
                ]],
            'логин = @example' => [[
                    'login' => '@example',
                    'code' => TempCode::generateCode(),
                ]],
            'логин = example.com' => [[
                    'login' => 'example.com',
                    'code' => TempCode::generateCode(),
                ]],
            'логин = .com' => [[
                    'login' => '.com',
                    'code' => TempCode::generateCode(),
                ]],
            'логин = @' => [[
                    'login' => '@',
                    'code' => TempCode::generateCode(),
                ]],
            'логин = *empty*' => [[
                    'login' => '',
                    'code' => TempCode::generateCode(),
                ]],
            'логин = null' => [[
                    'login' => null,
                    'code' => TempCode::generateCode(),
                ]],
            'логин = 1234567 (int)' => [[
                    'login' => 1234567,
                    'code' => TempCode::generateCode(),
                ]],
            'логин = 1234567891111 (int)' => [[
                    'login' => 1234567891111,
                    'code' => TempCode::generateCode(),
                ]],
            'логин = 79116015332 (int)' => [[
                    'login' => 79116015332,
                    'code' => TempCode::generateCode(),
                ]],
            'логин = 1234567 (str)' => [[
                    'login' => '1234567',
                    'code' => TempCode::generateCode(),
                ]],
            'логин = 1234567891111 (str)' => [[
                    'login' => '1234567891111',
                    'code' => TempCode::generateCode(),
                ]],
            'логин = []' => [[
                    'login' => [],
                    'code' => TempCode::generateCode(),
                ]],
            'логин = true' => [[
                    'login' => true,
                    'code' => TempCode::generateCode(),
                ]],

            // Валидный логин, невалидный код. Добавлен тут флаг, что бы наполнить базу с логином кодом
            'code = 999' => [[
                    'login' => Factory::create()->email(),
                    'code' => TempCode::RANDOM_GENERATE_LIMITS['min'] - 1,
                    'check_database' => true,
                ]],
            'code = 10000' => [[
                    'login' => '+79116015332',
                    'code' => TempCode::RANDOM_GENERATE_LIMITS['max'] + 1,
                    'check_database' => true,
                ]],
            'code = asd' => [[
                    'login' => '89116015333',
                    'code' => 'asd',
                    'check_database' => true,
                ]],

            // Валидный код и логин, но отсутствуют в базе
            'email отсутствие в БД' => [[
                    'login' => Factory::create()->email(),
                    'code' => TempCode::generateCode(),
                ]],
            'phone отсутствие в БД' => [[
                    'login' => '+79116015332',
                    'code' => TempCode::generateCode(),
                ]],
        ];
    }
}
