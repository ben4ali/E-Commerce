<?php

function getDateRange(): array
{
    $endDate = new DateTime(); // Today
    $startDate = (new DateTime())->modify('-30 days');

    return [
        'startDate' => $startDate->format('Y-m-d'),
        'endDate' => $endDate->format('Y-m-d')
    ];
}

function getPreviousDateRange(): array
{
    $startDate = (new DateTime())->modify('-60 days');
    $endDate = (new DateTime())->modify('-30 days');

    return [
        'startDate' => $startDate->format('Y-m-d'),
        'endDate' => $endDate->format('Y-m-d')
    ];
}

function calculatePercentageChange(array $currentData, array $previousData): array
{
    $percentageChange = [];

    foreach ($currentData as $key => $currentValue) {
        $previousValue = $previousData[$key] ?? 0;

        if ($previousValue > 0) {
            $change = (($currentValue - $previousValue) / $previousValue) * 100;
        } else {
            $change = ($currentValue > 0) ? 100 : 0;
        }

        $percentageChange[$key] = $change;
    }

    return $percentageChange;
}