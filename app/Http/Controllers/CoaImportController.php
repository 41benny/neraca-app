<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadCoaRequest;
use App\Services\CoaImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CoaImportController extends Controller
{
    public function __construct(private readonly CoaImportService $service) {}

    public function __invoke(UploadCoaRequest $request): JsonResponse|RedirectResponse
    {
        $result = $this->service->import($request->file('file'));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Import COA selesai.',
                'stats' => $result,
            ], 201);
        }

        return redirect()->back()->with('status', sprintf(
            'Import COA: akun +%d/%d, mapping +%d/%d, saldo awal +%d/%d',
            $result['accounts_created'],
            $result['accounts_updated'],
            $result['mappings_created'],
            $result['mappings_updated'],
            $result['openings_created'],
            $result['openings_updated'],
        ));
    }
}
