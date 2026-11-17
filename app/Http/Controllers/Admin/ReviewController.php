<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateReviewRequest;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        return view('admin.reviews.index', [
            'reviews' => Review::with(['user', 'title'])->latest()->paginate(20),
        ]);
    }

    public function update(UpdateReviewRequest $request, Review $review): RedirectResponse
    {
        $review->update($request->validated());

        return back()->with('status', 'Estado de la reseña actualizado correctamente.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('status', 'Reseña eliminada correctamente.');
    }
}
