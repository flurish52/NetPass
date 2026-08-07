<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PlanController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Plan::class);

        $plans = auth()->user()->business->plans()
            ->latest()
            ->get();

        return Inertia::render('Plans/Index', [
            'plans' => $plans,
        ]);
    }

    public function store(StorePlanRequest $request): RedirectResponse
    {
        auth()->user()->business->plans()->create($request->validated());

        return back()->with('success', 'Plan created.');
    }

    public function update(UpdatePlanRequest $request, Plan $plan): RedirectResponse
    {
        $plan->update($request->validated());

        return back()->with('success', 'Plan updated.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        $this->authorize('delete', $plan);

        $plan->delete();

        return back()->with('success', 'Plan deleted.');
    }
}
