<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $user = $request->user();
        $member = $user->member;
        $today = today();
        $weekStart = $today->copy()->startOfWeek();
        $monthStart = $today->copy()->startOfMonth();
        $chartStart = $today->copy()->subDays(6);

        if ($user->role === 'admin') {
            return Inertia::render('Dashboard', [
                'role' => 'admin',
                'admin' => [
                    'collection' => [
                        'titles' => Book::count(),
                        'copies' => BookCopy::count(),
                        'available' => BookCopy::where('status', 'tersedia')->count(),
                        'borrowed' => BookCopy::where('status', 'dipinjam')->count(),
                        'damaged' => BookCopy::whereIn('status', ['rusak', 'hilang'])->count(),
                    ],
                    'loans' => [
                        'today' => Loan::whereDate('tanggal_pinjam', $today)->count(),
                        'week' => Loan::whereBetween('tanggal_pinjam', [$weekStart, $today])->count(),
                        'month' => Loan::whereBetween('tanggal_pinjam', [$monthStart, $today])->count(),
                        'active' => Loan::whereIn('status', ['dipinjam', 'sebagian_kembali', 'terlambat'])->count(),
                        'overdue' => Loan::where('status', 'terlambat')->count(),
                    ],
                    'trend' => [
                        'labels' => $this->chartLabels($chartStart, $today),
                        'loans' => $this->dailyCounts(Loan::whereBetween('tanggal_pinjam', [$chartStart, $today])->get(), 'tanggal_pinjam', $chartStart, $today),
                        'visits' => $this->dailyCounts(Visit::whereBetween('waktu_masuk', [$chartStart->startOfDay(), $today->endOfDay()])->get(), 'waktu_masuk', $chartStart, $today),
                    ],
                    'recentActivities' => ActivityLog::query()->with('user:id,nama')->latest()->limit(6)->get(['id', 'user_id', 'action', 'description', 'created_at']),
                ],
            ]);
        }

        $memberLoans = $member?->loans() ?? Loan::query()->whereRaw('1 = 0');

        return Inertia::render('Dashboard', [
            'role' => $user->role,
            'member' => [
                'activeLoans' => (clone $memberLoans)->whereIn('status', ['dipinjam', 'sebagian_kembali', 'terlambat'])->count(),
                'overdueLoans' => (clone $memberLoans)->where('status', 'terlambat')->count(),
                'completedLoans' => (clone $memberLoans)->where('status', 'selesai')->count(),
                'visitsThisMonth' => $member?->visits()->whereBetween('waktu_masuk', [$monthStart->startOfDay(), $today->endOfDay()])->count() ?? 0,
                'unpaidFines' => Fine::query()->whereIn('status', ['belum_lunas', 'sebagian'])->whereHas('loanItem.loan', fn ($query) => $query->where('member_id', $member?->id))->sum('jumlah'),
                'recentLoans' => $member?->loans()->latest('tanggal_pinjam')->limit(5)->get(['id', 'kode_transaksi', 'tanggal_pinjam', 'batas_kembali', 'status']) ?? collect(),
            ],
        ]);
    }

    private function chartLabels(Carbon $start, Carbon $end): array
    {
        return collect(range(0, $start->diffInDays($end)))->map(fn (int $days): string => $start->copy()->addDays($days)->format('d M'))->all();
    }

    private function dailyCounts($items, string $dateColumn, Carbon $start, Carbon $end): array
    {
        $counts = $items->groupBy(fn ($item): string => Carbon::parse($item->{$dateColumn})->toDateString())->map->count();

        return collect(range(0, $start->diffInDays($end)))->map(fn (int $days): int => $counts->get($start->copy()->addDays($days)->toDateString(), 0))->all();
    }
}
