<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $pendingEvents = Event::with('organiser')->pending()->get();
        $organisersCount = User::where('role', 'organiser')->count();
        $customersCount = User::where('role', 'customer')->count();
        $totalPlatformRevenue = Transaction::where('status', 'paid')->sum('platform_fee');

        $organisers = User::where('role', 'organiser')
            ->withCount('events')
            ->paginate(5, ['*'], 'orgs');

        // Summary calculations for organisers
        $organisers->getCollection()->transform(function($org) {
            $stats = DB::table('bookings')
                ->join('ticket_types', 'bookings.ticket_type_id', '=', 'ticket_types.id')
                ->join('events', 'ticket_types.event_id', '=', 'events.id')
                ->where('events.organiser_id', $org->id)
                ->where('bookings.status', 'confirmed')
                ->selectRaw('SUM(bookings.quantity) as total_tickets, SUM(bookings.total_price) as total_revenue')
                ->first();
            
            $org->total_tickets = $stats->total_tickets ?? 0;
            $org->total_revenue = $stats->total_revenue ?? 0;
            return $org;
        });

        $customers = User::where('role', 'customer')
            ->withCount(['bookings' => function($q) {
                $q->where('status', 'confirmed');
            }])
            ->withSum(['bookings' => function($q) {
                $q->where('status', 'confirmed');
            }], 'total_price')
            ->paginate(5, ['*'], 'custs');

        $processedEvents = Event::with('organiser')
            ->whereIn('status', ['published', 'rejected'])
            ->latest('updated_at')
            ->paginate(10, ['*'], 'history');

        return view('admin.dashboard', compact(
            'pendingEvents', 
            'organisersCount', 
            'customersCount', 
            'totalPlatformRevenue',
            'organisers',
            'customers',
            'processedEvents'
        ));
    }

    public function users(Request $request)
    {
        $role = $request->query('role');
        $users = User::when($role, fn($q) => $q->where('role', $role))->paginate(20);
        
        return view('admin.users', compact('users', 'role'));
    }

    public function revenue()
    {
        $transactions = Transaction::with('booking.ticketType.event')->paginate(20);
        $totalSales = Transaction::where('status', 'paid')->sum('amount');
        $platformRevenue = Transaction::where('status', 'paid')->sum('platform_fee');

        return view('admin.revenue', compact('transactions', 'totalSales', 'platformRevenue'));
    }

    public function approveEvent(Event $event)
    {
        $event->update(['status' => 'published']);
        return back()->with('success', 'Event published.');
    }

    public function rejectEvent(Event $event, Request $request)
    {
        $request->validate(['rejection_reason' => 'required|string']);
        $event->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);
        return back()->with('success', 'Event rejected.');
    }
}
