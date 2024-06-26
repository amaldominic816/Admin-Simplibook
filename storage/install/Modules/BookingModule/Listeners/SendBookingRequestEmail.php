<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingStatusHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('booking_id');
            $table->foreignUuid('changed_by');
            $table->string('booking_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_status_histories');
    }
}
                                                                                                                                                                                                                                                               <?php

namespace Modules\BookingModule\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\BookingModule\Emails\BookingMail;
use Modules\BookingModule\Events\BookingRequested;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBookingRequestEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param BookingRequested $event
     * @return void
     */
    public function handle(BookingRequested $event)
    {
        try {
            Mail::to($event->booking->customer->email)->send(new BookingMail($event->booking));
        } catch (\Exception $exception) {
            info($exception);
        }

        $config = business_config('booking', 'notification_settings');
        if ($config->live_values['push_notification_booking']) {

            $data = business_config('booking_place', 'notification_messages');
            if (isset($event->booking->customer->fcm_token)) {
                device_notification($event->booking->customer->fcm_token, $data->live_values['booking_place_message'], null, null, $event->booking->id, 'booking');
            }
        }
    }
}
