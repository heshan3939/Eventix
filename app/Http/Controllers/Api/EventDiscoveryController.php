<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Booking;
use App\Http\Resources\EventResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventDiscoveryController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get a collection of "useful" event data for the user dashboard/homepage.
     */
    public function index()
    {
        // 1. Trending Events (Events with the most confirmed bookings in the last 7 days)
        $trendingEvents = Event::where('status', 'published')
            ->where('starts_at', '>', now())
            ->withCount(['bookings' => function($query) {
                $query->where('status', 'confirmed');
            }])
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();

        // 2. Newly Added Events
        $recentEvents = Event::where('status', 'published')
            ->where('starts_at', '>', now())
            ->latest()
            ->take(5)
            ->get();

        // 3. Category Summary (Useful for displaying filter chips)
        $categories = Event::select('category', DB::raw('count(*) as total'))
            ->where('status', 'published')
            ->where('starts_at', '>', now())
            ->groupBy('category')
            ->get();

        $data = [
            'trending' => EventResource::collection($trendingEvents),
            'recent' => EventResource::collection($recentEvents),
            'categories' => $categories,
            'stats' => [
                'total_active_events' => Event::where('status', 'published')->where('starts_at', '>', now())->count(),
                'total_venues' => Event::distinct('venue')->count(),
            ]
        ];

        return $this->successResponse($data, 'Useful discovery data retrieved successfully.');
    }

    /**
     * Search events with advanced filters.
     */
    public function search(Request $request)
    {
        $query = Event::where('status', 'published')
            ->where('starts_at', '>', now());

        if ($request->has('q')) {
            $query->where('title', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%');
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        $events = $query->with('organiser', 'ticketTypes')->paginate(10);

        return $this->paginatedResponse($events, 'Search results retrieved.');
    }
}
