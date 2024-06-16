<?php

namespace Modules\BookingModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuid;

class BookingOfflinePayment extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'booking_id',
        'customer_information',
    ];
    protected $casts = [
        'customer_information' => 'array',
    ];

    protected static function<?php

namespace Modules\BookingModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\UserManagement\Entities\User;

class BookingScheduleHistory extends Model
{
    use HasFactory;

    protected $fillable = [];


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
