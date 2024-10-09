<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = true;
    protected function getStats(): array
    {
        $pemasukan = Transaction::incomes()->get()->sum('amount');
        $pengeluaran = Transaction::expenses()->get()->sum('amount');
        $selisih = $pemasukan - $pengeluaran;
        // $pemasukan = Transaction::join('categories', 'transactions.category_id', '=', 'categories.id')->where('is_expense', false)->sum('amount');
        // $pengeluaran = Transaction::join('categories', 'transactions.category_id', '=', 'categories.id')->where('is_expense', true)->sum('amount');
        return [
            //
            Stat::make('Total Pemasukan', $pemasukan)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->description('increase')
                ->color('success'),
            Stat::make('Total Pengeluaran', $pengeluaran)
                ->description('decrease')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
            Stat::make('Selisih', $selisih)
                ->description('money on pocket')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('info'),
        ];
    }
}
