<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadJournalRequest;
use App\Http\Resources\Accounting\JournalImportResource;
use App\Services\JournalImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class JournalImportController extends Controller
{
    public function __construct(private readonly JournalImportService $journalImportService) {}

    public function __invoke(UploadJournalRequest $request): JsonResponse|RedirectResponse
    {
        $result = $this->journalImportService->import(
            file: $request->file('file'),
            batchName: $request->string('import_name')->trim()->toString(),
            userId: $request->user()?->id,
            importedAt: $request->input('imported_at'),
        );

        if ($request->expectsJson()) {
            return (new JournalImportResource($result))
                ->additional([
                    'message' => 'Upload jurnal berhasil diproses.',
                ])
                ->response($request)
                ->setStatusCode(201);
        }

        return redirect()
            ->back()
            ->with('status', 'Upload jurnal berhasil: '.$result->createdLines.' baris.');
    }
}
