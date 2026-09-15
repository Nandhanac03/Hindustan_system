<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OpeningBalanceController extends Controller
{
    public function index(Request $request)
    {
        return view('chart-of-accounts.index');
    }

    public function save(Request $request): RedirectResponse
    {
        return redirect()->back()->with('status', 'Opening balances saved.');
    }

    public function lock(Request $request): RedirectResponse
    {
        return redirect()->back()->with('status', 'Opening balances locked.');
    }

    public function unlock(Request $request): RedirectResponse
    {
        return redirect()->back()->with('status', 'Opening balances unlocked.');
    }
}
