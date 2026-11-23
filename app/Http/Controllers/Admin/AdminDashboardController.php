<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Person;
use App\Models\Review;
use App\Models\Title;
use App\Models\User;
use App\Models\Rating;
use App\Models\Comment;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalTitles' => Title::count(),
            'publishedTitles' => Title::where('status', 'published')->count(),
            'draftTitles' => Title::where('status', 'draft')->count(),
            'totalGenres' => Genre::count(),
            'totalPeople' => Person::count(),
            'totalUsers' => User::count(),
            'pendingReviews' => Review::where('status', 'pending')->count(),
            'approvedReviews' => Review::where('status', 'approved')->count(),
            'hiddenReviews' => Review::where('status', 'hidden')->count(),
            'totalRatings' => Rating::count(),
            'totalComments' => Comment::count(),
            'averageRating' => Rating::avg('score'),
            'topRatedTitles' => Title::where('status', 'published')
                ->withAvg('ratings', 'score')
                ->havingRaw('ratings_avg_score IS NOT NULL')
                ->orderByDesc('ratings_avg_score')
                ->limit(5)
                ->get(),
        ]);
    }
}
