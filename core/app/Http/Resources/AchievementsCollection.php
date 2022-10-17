<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\DB;

class AchievementsCollection extends BaseCollection
{
    public $collects = AchievementResource::class;

    /**
     * Кол-во ручных достижений
     * @var int
     */
    public int $countManual = 0;

    /**
     * Кол-во автодостижений
     * @var int
     */
    public int $countAuto = 0;

    /**
     * @param $resource
     * @param int $countAuto
     * @param int $countManual
     */
    public function __construct($resource, int $countAuto, int $countManual)
    {
        $this->countAuto = $countAuto;
        $this->countManual = $countManual;
        parent::__construct($resource);
    }

    /**
     * Добавляем подсчет кол-ва автодостижений и внесенных вручную
     *
     * @param $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        $data = [
            'status' => true,
            'count_auto' => $this->countAuto,
            'count_manual' => $this->countManual,
            'data' => $this->collection
        ];

        if (config('app.debug')) {
            $data += [
                'request' => $request->all(),
                'debug' => DB::getQueryLog()
            ];
        }

        return $data;
    }
}
