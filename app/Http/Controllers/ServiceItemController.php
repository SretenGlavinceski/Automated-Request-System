<?php

namespace App\Http\Controllers;

use App\Models\ServiceItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceItemController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $serviceItems = ServiceItem::latest()->get();

        return view('service-items.index', compact('serviceItems'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        return view('service-items.create');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        ServiceItem::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('service-items.index')
            ->with('success', 'Service item created successfully.');
    }

    public function edit(ServiceItem $serviceItem): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        return view('service-items.edit', compact('serviceItem'));
    }

    public function update(
        Request $request,
        ServiceItem $serviceItem
    ): RedirectResponse {
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $serviceItem->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('service-items.index')
            ->with('success', 'Service item updated successfully.');
    }

    public function destroy(ServiceItem $serviceItem): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $serviceItem->delete();

        return redirect()
            ->route('service-items.index')
            ->with('success', 'Service item deleted successfully.');
    }
}
