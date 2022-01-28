<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckStatusRequest;
use App\Services\TimelineService\Facades\Timeline;
use App\Traits\ApiTools;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class WorkingDayController extends Controller
{
    use ApiTools;

    /**
     * @return View
     */
    public function index(): View
    {
        return view('index');
    }

    /**
     * @return JsonResponse
     */
    public function getOpenInfo(): JsonResponse
    {
        $dateTime = now();
        $status = Timeline::getOpenInfo($dateTime);
        $data['isOpen'] = $status['open'];
        $data['isLunch'] = $status['lunch'];
        if (!$data['isOpen']) {
            $data['nextOpenTime'] = Timeline::getNextOpeningTime($dateTime);
        }
        return $this->success($data);
    }

    /**
     * @param CheckStatusRequest $request
     * @return JsonResponse
     */
    public function checkStatus(CheckStatusRequest $request): JsonResponse
    {
        $isOpen = Timeline::checkStatus($request->date);
        return $this->success(compact('isOpen'));
    }

}
