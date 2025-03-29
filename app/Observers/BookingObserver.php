<?php

namespace App\Observers;

use App\Models\BookingModel;
use Carbon\Carbon;


class BookingObserver
{
    /**
     * Handle the BookingModel "created" event.
     */
    public function created(BookingModel $bookingModel): void
    {
        //

        $nights = Carbon::parse($booking->check_in)->diffInDays(Carbon::parse($booking->check_out));
        $rooms = RoomModel::whereIn('id', json_decode($booking->room_ids))->get();
        $booking->total_price = $rooms->sum('room_price') * $nights;
        $booking->number_of_guests = $booking->number_of_guests ?? $rooms->sum('capacity');
    }

    /**
     * Handle the BookingModel "updated" event.
     */
    public function updated(BookingModel $bookingModel): void
    {
        //
    }

    /**
     * Handle the BookingModel "deleted" event.
     */
    public function deleted(BookingModel $bookingModel): void
    {
        //
    }

    /**
     * Handle the BookingModel "restored" event.
     */
    public function restored(BookingModel $bookingModel): void
    {
        //
    }

    /**
     * Handle the BookingModel "force deleted" event.
     */
    public function forceDeleted(BookingModel $bookingModel): void
    {
        //
    }
}
