<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateReviewRequest;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'status' => ['nullable', 'in:pending,approved,hidden,rejected'],
        ]);

        return view('admin.reviews.index', [
            'reviews' => Review::with(['user', 'title'])
                ->when($validated['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'selectedStatus' => $validated['status'] ?? '',
        ]);
    }

    public function update(UpdateReviewRequest $request, Review $review): RedirectResponse
    {
        $review->update($request->validated());

        return back()->with('status', 'Estado de la reseña actualizado correctamente.');
    }

    public function updateStatus(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,hidden,pending'],
        ]);

        $review->update(['status' => $validated['status']]);

        return redirect()->route('admin.reviews.index')
            ->with('status', 'El estado de la reseña se ha actualizado.');
    }

    public function bulkStatus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'review_ids' => ['required', 'array', 'min:1'],
            'review_ids.*' => ['integer', 'exists:reviews,id'],
            'status' => ['required', 'in:approved,hidden,pending,rejected'],
        ]);

        Review::whereKey($validated['review_ids'])
            ->update(['status' => $validated['status']]);

        return back()->with('status', 'Las reseñas seleccionadas se han actualizado.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('status', 'Reseña eliminada correctamente.');
    }
}
