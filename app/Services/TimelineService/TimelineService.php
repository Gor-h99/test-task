<?php

namespace App\Services\TimelineService;

use Carbon\Carbon;

class TimelineService
{
    private const CONFIG_PREFIX = 'timeline.';

    /**
     * @param string $key
     * @param $default
     * @return mixed
     */
    private function config(string $key, $default = null)
    {
        return config(self::CONFIG_PREFIX . $key, $default);
    }

    /**
     * @param Carbon $dateTime
     * @return bool
     */
    private function isWorkingDay(Carbon $dateTime): bool
    {
        return in_array($dateTime->dayOfWeek, $this->config('working_days', []));
    }

    /**
     * @param Carbon $dateTime
     * @return bool
     */
    private function isOtherWeekWorkingDay(Carbon $dateTime): bool
    {
        if (!in_array($dateTime->dayOfWeek, $this->config('other_week_working_days'))) {
            return false;
        }
        return $this->isOtherWorkingWeek($dateTime);
    }

    /**
     * @param Carbon $dateTime
     * @return bool
     */
    private function isOtherWorkingWeek(Carbon $dateTime): bool
    {
        $diff = $dateTime->clone()->startOfWeek()->diffInWeeks($this->config('other_week_calculate_from'));
        return $diff % 2 === 1;
    }

    /**
     * @param Carbon $dateTime
     * @return array
     */
    private function isWorkingTime(Carbon $dateTime): array
    {
        $workingBetween = $this->config('working_between');
        $lunchBetween = $this->config('lunch_between');
        $workStart = Carbon::createFromTimeString($workingBetween[0]);
        $workEnd = Carbon::createFromTimeString($workingBetween[1]);
        $lunchStart = Carbon::createFromTimeString($lunchBetween[0]);
        $lunchEnd = Carbon::createFromTimeString($lunchBetween[1]);
        $data = [
            'open' => false,
            'lunch' => false,
        ];
        if ($dateTime->between($workStart, $workEnd)) {
            $data['open'] = true;
            if ($dateTime->between($lunchStart, $lunchEnd)) {
                $data['lunch'] = true;
            }
        }

        return $data;
    }

    /**
     * @param bool $isOtherWeek
     * @return array
     */
    private function getWorkingDays(bool $isOtherWeek): array
    {
        $workingDays = $this->config('working_days', []);
        if ($isOtherWeek) {
            $workingDays = array_merge($workingDays, $this->config('other_week_working_days', []));
        }
        sort($workingDays);
        return $workingDays;
    }

    /**
     * @param $dateTime
     * @return string
     */
    public function getNextOpeningTime($dateTime): string
    {
        $workStart = $this->config('working_between')[0];
        $isOtherWorkingWeek = $this->isOtherWorkingWeek($dateTime);
        $workingDays = $this->getWorkingDays($isOtherWorkingWeek);
        foreach ($workingDays as $workingDay) {
            if ($dateTime->dayOfWeek === $workingDay && $dateTime->lt($workStart) || $dateTime->dayOfWeek < $workingDay) {
                $nextWorkingDay = $workingDay;
                break;
            }
        }
        if (!isset($nextWorkingDay)) {
            $nextWorkingDay = $this->getWorkingDays(!$isOtherWorkingWeek)[0];
        }
        return $dateTime->clone()->next($nextWorkingDay)->setTime($workStart, 0, 0)->diffForHumans(['parts' => 2]);
    }

    /**
     * @param Carbon $dateTime
     * @return array
     */
    public function getOpenInfo(Carbon $dateTime): array
    {
        if (!$this->isWorkingDay($dateTime) && !$this->isOtherWeekWorkingDay($dateTime)) {
            return [
                'open' => false,
                'lunch' => false,
            ];
        }
        return $this->isWorkingTime($dateTime);
    }

    /**
     * @param string $date
     * @return bool
     */
    public function checkStatus(string $date): bool
    {
        $dateTime = new Carbon($date);
        if (!$this->isWorkingDay($dateTime) && !$this->isOtherWeekWorkingDay($dateTime)) {
            return false;
        } else {
            return true;
        }
    }
}
