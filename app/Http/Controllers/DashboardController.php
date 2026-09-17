<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $user = $request->user();
        $member = $user->member;
        $loanQuery = Loan::query();

        if ($user->role !== 'admin' && $member !== null) {
            $loanQuery->whereBelongsTo($member);
        }

        return Inertia::render('Dashboard', [
            'stats' => [
                'activeLoans' => (clone $loanQuery)->whereIn('status', ['dipinjam', 'sebagian_kembali', 'terlambat'])->count(),
                'completedLoans' => (clone $loanQuery)->where('status', 'selesai')->count(),
                'overdueLoans' => (clone $loanQuery)->where('status', 'terlambat')->count(),
                'role' => $user->role,
            ],
        ]);
    }
}
