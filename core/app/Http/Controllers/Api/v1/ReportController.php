<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\ActivityEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\{DestroyReportRequest,
    GetReportRequest,
    PaginationRequest,
    StoreReportRequest,
    UpdateReportRequest};
use App\Http\Resources\{BaseResource, ReportResource, ReportsCollection};
use App\Models\{Report, Setting};
use App\Services\FileService;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    /**
     * Сервисный слой для работы с файлами
     *
     * @var FileService
     */
    public FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Возвращает список отчетов
     *
     * @param PaginationRequest $request
     *
     * @return ReportsCollection
     */
    public function index(PaginationRequest $request): ReportsCollection
    {
        return new ReportsCollection(
            $request->user()->reports()->with([
                'goal',
                'comments' => fn($q) => $q->with('user')
            ])
                ->withCount('comments')
                ->paginate($request->per_page ?? 12)
        );
    }

    /**
     * Добавить новый отчет
     *
     * @param StoreReportRequest $request
     *
     * @return BaseResource
     */
    public function store(StoreReportRequest $request): BaseResource
    {
        $imageInfo = $request->hasFile('photo')
            ? ['file' => $this->fileService->saveImage($request->photo)]
            : [];
        $newReport = $request->user()->reports()->create($request->validated() + $imageInfo);
        event(new ActivityEvent($request->user(), Setting::NEW_DAILY_REPORT));

        return new BaseResource([
            'message' => 'Report stored successfully',
            'data' => [
                'id' => $newReport->id,
            ],
        ]);
    }

    /**
     * Возвращает информацию об отчете
     *
     * @param GetReportRequest $request
     * @param Report $report
     *
     * @return ReportResource
     */
    public function show(GetReportRequest $request, Report $report): ReportResource
    {
        return new ReportResource($report->load(['goal', 'comments', 'user'])->loadCount('comments'));
    }

    /**
     * Обновить отчет
     *
     * @param UpdateReportRequest $request
     * @param Report $report
     *
     * @return Response
     */
    public function update(UpdateReportRequest $request, Report $report): Response
    {
        $imageInfo = $request->hasFile('photo')
            ? ['file' => $this->fileService->saveImage($request->photo)]
            : [];

        return response([
            'status' => $report->update($request->validated() + $imageInfo),
            'message' => 'The report has been updated',
        ]);
    }

    /**
     * Удалить отчет
     *
     * @param DestroyReportRequest $request
     * @param Report $report
     *
     * @return Response
     */
    public function destroy(DestroyReportRequest $request, Report $report): Response
    {
        return response([
            'status' => (bool)$report->delete(),
            'message' => 'The report has been deleted',
        ]);
    }
}
