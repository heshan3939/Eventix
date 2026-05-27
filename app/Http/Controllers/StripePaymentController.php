<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Mail\OrderReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\RedirectResponse;

class StripePaymentController extends Controller
{
    /**
     * Create a Stripe Checkout Session and redirect.
     */
    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'booking_ids'   => 'required|array|min:1',
            'booking_ids.*' => 'exists:bookings,id',
        ]);

        $bookings = Booking::with('ticketType.event')
            ->whereIn('id', $validated['booking_ids'])
            ->where('user_id', $request->user()->id)
            ->where('status', 'pending')
            ->get();

        if ($bookings->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'No valid pending bookings found.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        // Build one line item per booking
        $lineItems = $bookings->map(function ($booking) {
            $event = $booking->ticketType->event;
            return [
                'price_data' => [
                    'currency'     => 'gbp',
                    'unit_amount'  => (int) ($booking->total_price * 100),
                    'product_data' => [
                        'name'        => $event->title,
                        'description' => "{$booking->ticketType->name} × {$booking->quantity}",
                    ],
                ],
                'quantity' => 1,
            ];
        })->values()->all();

        try {
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items'           => $lineItems,
                'mode'                 => 'payment',
                'success_url'          => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'           => route('payment.cancel') . '?session_id={CHECKOUT_SESSION_ID}',
                'metadata'             => [
                    'booking_ids' => implode(',', $bookings->pluck('id')->all()),
                ],
            ]);

            // Mark all bookings with the session ID
            $bookings->each(function ($booking) use ($session) {
                $booking->stripe_session_id = $session->id;
                $booking->payment_status    = 'pending';
                $booking->save();
            });

            return redirect($session->url);
        } catch (\Exception $e) {
            Log::error('Stripe checkout creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to initiate payment. Please try again later.');
        }
    }

    /**
     * Handle successful payment.
     */
    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (! $sessionId) {
            return redirect()->route('dashboard')->with('error', 'Invalid payment session.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $session    = StripeSession::retrieve($sessionId);
        $bookingIds = array_filter(explode(',', $session->metadata->booking_ids ?? ''));

        $bookings = Booking::with(['ticketType.event', 'user'])->whereIn('id', $bookingIds)->get();
        $shouldSendReceipt = false;

        foreach ($bookings as $booking) {
            if ($booking->payment_status !== 'paid') {
                $booking->payment_status = 'paid';
                $booking->status         = 'confirmed';
                $booking->save();
                $shouldSendReceipt = true;
            }
        }

        // Send email receipt to user if we confirmed new paid bookings
        if ($shouldSendReceipt && $bookings->isNotEmpty()) {
            $user = $bookings->first()->user;
            if ($user && $user->email) {
                try {
                    Mail::to($user->email)->send(new OrderReceipt($bookings));
                } catch (\Exception $e) {
                    Log::error('Failed to send order receipt email for bookings: ' . implode(',', $bookingIds) . '. Error: ' . $e->getMessage());
                }
            }
        }

        return view('payment.success', compact('bookings'));
    }

    /**
     * Handle canceled payment.
     */
    public function cancel(Request $request)
    {
        return view('payment.cancel');
    }
}
