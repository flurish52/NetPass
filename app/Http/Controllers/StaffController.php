<?php

namespace App\Http\Controllers;

use App\Actions\CreateStaffAccount;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StaffController extends Controller
{
    public function index(): Response
    {
        $this->authorize('manage staff');

        $business = auth()->user()->business;

        $staff = $business->users()
            ->role('staff')
            ->latest()
            ->get(['id', 'name', 'email', 'created_at']);

        return Inertia::render('Staff/Index', [
            'staff' => $staff,
        ]);
    }

    public function store(Request $request, CreateStaffAccount $action): RedirectResponse
    {
        $this->authorize('manage staff');

        $business = auth()->user()->business;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        ]);

        $result = $action->handle($business, $data);

        return back()->with('generatedCredentials', [
            'email' => $result['user']->email,
            'password' => $result['password'],
        ]);
    }

    public function destroy(User $staff): RedirectResponse
    {
        $this->authorize('manage staff');

        abort_unless($staff->business_id === auth()->user()->business_id, 403);
        abort_unless($staff->hasRole('staff'), 403);

        $staff->delete();

        return back()->with('success', 'Staff removed.');
    }
}
