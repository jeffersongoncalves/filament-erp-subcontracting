<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use JeffersonGoncalves\Erp\Core\Enums\DocStatus;
use JeffersonGoncalves\Erp\Subcontracting\Enums\SubcontractingOrderStatus;
use JeffersonGoncalves\Erp\Subcontracting\Support\ModelResolver;

/**
 * How many subcontracting orders are live with suppliers — submitted but not
 * yet completed, cancelled or closed — and the total value they represent.
 */
class SubcontractingOrderStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $orderModel = ModelResolver::subcontractingOrder();

        $open = $orderModel::query()
            ->where('docstatus', DocStatus::Submitted->value)
            ->whereNotIn('status', [
                SubcontractingOrderStatus::Completed->value,
                SubcontractingOrderStatus::Cancelled->value,
                SubcontractingOrderStatus::Closed->value,
            ]);

        $openCount = (clone $open)->count();
        $openTotal = (float) (clone $open)->sum('grand_total');

        $submitted = $orderModel::query()
            ->where('docstatus', DocStatus::Submitted->value)
            ->count();

        return [
            Stat::make('Open Subcontracting Orders', (string) $openCount)
                ->description(number_format($openTotal, 2).' total value')
                ->color($openCount > 0 ? 'primary' : 'gray'),
            Stat::make('Submitted Subcontracting Orders', (string) $submitted)
                ->description('total submitted')
                ->color('success'),
        ];
    }
}
