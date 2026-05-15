<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'venue'          => ['required', 'string', 'max:255'],
            'city'           => ['required', 'string', 'max:100'],
            'category'       => ['required', 'in:music,conference,culture,sports,education'],
            'starts_at'      => ['required', 'date', 'after:now'],
            'ends_at'        => ['nullable', 'date', 'after:starts_at'],
            'total_capacity' => ['required', 'integer', 'min:1', 'max:100000'],
            'tickets'        => [$this->isMethod('POST') ? 'required' : 'nullable', 'array', 'min:1'],
            'tickets.*.name' => ['required_with:tickets', 'string', 'max:255'],
            'tickets.*.price' => ['required_with:tickets', 'numeric', 'min:0'],
            'tickets.*.quantity' => ['required_with:tickets', 'integer', 'min:1'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $totalCapacity = (int) $this->input('total_capacity');
            $tickets = $this->input('tickets');

            if (is_array($tickets)) {
                $sumQuantities = collect($tickets)->sum('quantity');
            } else {
                // For updates where tickets aren't provided in the request
                $event = $this->route('event');
                $sumQuantities = $event ? $event->ticketTypes()->sum('quantity') : 0;
            }

            if ($sumQuantities > $totalCapacity) {
                $validator->errors()->add('total_capacity', "The event capacity ($totalCapacity) cannot be less than the total quantity of all ticket tiers ($sumQuantities).");
            }
        });
    }
}
