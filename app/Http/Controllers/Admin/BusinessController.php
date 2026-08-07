<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CreateBusinessAccount;
use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BusinessController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Businesses/Index', [
            'businesses' => Business::withCount('vouchers')
                ->latest()
                ->get()
                ->map(fn ($business) => [
                    'id' => $business->id,
                    'name' => $business->name,
                    'subscriptionStatus' => $business->subscription_status,
                    'voucherCount' => $business->vouchers_count,
                    'createdAt' => $business->created_at->toIso8601String(),
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Businesses/Create');
    }

    public function store(Request $request, CreateBusinessAccount $action): RedirectResponse
    {
        $data = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        ]);

        $result = $action->handle($data);

        return redirect()
            ->route('admin.businesses.index')
            ->with('generatedCredentials', [
                'email' => $result['user']->email,
                'password' => $result['password'],
            ]);
    }
}
