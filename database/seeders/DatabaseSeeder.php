<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Event;
use App\Models\TicketType;
use App\Models\Booking;
use App\Models\Transaction;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(['email' => 'admin@eventix.com'], [
            'name' => 'Eventix Admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $organiser = User::firstOrCreate(['email' => 'organiser@eventix.com'], [
            'name' => 'Jane Organiser',
            'password' => Hash::make('password'),
            'role' => 'organiser',
            'email_verified_at' => now(),
        ]);

        $customer = User::firstOrCreate(['email' => 'customer@eventix.com'], [
            'name' => 'Nipuna Customer',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        $eventsData = [
            [
                'title' => 'Colombo Music Fest 2025',
                'category' => 'music',
                'city' => 'Colombo',
                'venue' => 'Viharamahadevi Park',
                'total_capacity' => 3000,
                'starts_at' => '2025-08-15 18:00:00'
            ],
            [
                'title' => 'Tech Summit Sri Lanka',
                'category' => 'conference',
                'city' => 'Colombo',
                'venue' => 'BMICH',
                'total_capacity' => 500,
                'starts_at' => '2025-09-20 09:00:00'
            ],
            [
                'title' => 'Kandy Cultural Night',
                'category' => 'culture',
                'city' => 'Kandy',
                'venue' => 'Temple of the Tooth',
                'total_capacity' => 800,
                'starts_at' => '2025-10-05 19:00:00'
            ],
            [
                'title' => 'Galle Literary Festival 2025',
                'category' => 'education',
                'city' => 'Galle',
                'venue' => 'Galle Fort',
                'total_capacity' => 400,
                'starts_at' => '2025-11-01 10:00:00'
            ]
        ];

        foreach ($eventsData as $data) {
            $data['organiser_id'] = $organiser->id;
            $data['status'] = 'published';
            $data['description'] = "Join us for the amazing {$data['title']} in {$data['city']}!";
            
            $event = Event::firstOrCreate(['title' => $data['title']], $data);

            $generalQty = (int)($event->total_capacity * 0.70);
            $vipQty = (int)($event->total_capacity * 0.30);

            $genType = TicketType::firstOrCreate(
                ['event_id' => $event->id, 'name' => 'General'],
                [
                    'price' => 25.00, 
                    'quantity' => $generalQty, 
                    'max_per_booking' => 10
                ]
            );

            $vipType = TicketType::firstOrCreate(
                ['event_id' => $event->id, 'name' => 'VIP'],
                [
                    'price' => 75.00, 
                    'quantity' => $vipQty, 
                    'max_per_booking' => 5
                ]
            );

            // Create 1 confirmed booking for this event
            $booking = Booking::firstOrCreate(
                ['user_id' => $customer->id, 'ticket_type_id' => $genType->id],
                [
                    'quantity' => 2,
                    'total_price' => 50.00,
                    'reference' => 'EVX-' . strtoupper(Str::random(8)),
                    'status' => 'confirmed'
                ]
            );

            Transaction::firstOrCreate(
                ['booking_id' => $booking->id],
                [
                    'amount' => 50.00,
                    'platform_fee' => 2.50,
                    'status' => 'paid',
                    'payment_method' => 'card'
                ]
            );
        }
    }
}
