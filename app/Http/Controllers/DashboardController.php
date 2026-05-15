<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.index');
        }

        if ($user->isOrganiser()) {
            $events = Event::where('organiser_id', $user->id)
                ->withCount(['bookings' => function($q) {
                    $q->where('status', 'confirmed');
                }])
                ->get();
                
            return view('dashboard', compact('events'));
        }

        // Customer
        $bookings = $user->bookings()
            ->with(['ticketType.event'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('dashboard', compact('bookings'));
    }
}
