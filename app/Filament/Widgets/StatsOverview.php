<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = true;
    protected function getStats(): array
    {
        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        $pemasukan = Transaction::incomes()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');
        $pengeluaran = Transaction::expenses()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');
        $selisih = $pemasukan - $pengeluaran;
        // $pemasukan = Transaction::join('categories', 'transactions.category_id', '=', 'categories.id')->where('is_expense', false)->sum('amount');
        // $pengeluaran = Transaction::join('categories', 'transactions.category_id', '=', 'categories.id')->where('is_expense', true)->sum('amount');
        return [
            //
            Stat::make('Total Pemasukan', 'Rp.' . ' ' . $pemasukan)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->description('increase')
                ->color('success'),
            Stat::make('Total Pengeluaran', 'Rp.' . ' ' . $pengeluaran)
                ->description('decrease')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
            Stat::make('Selisih', 'Rp.' . ' ' . $selisih)
                ->description('money on pocket')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('info'),
        ];
    }
}
