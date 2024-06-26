<?php

namespace Modules\ProviderManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TransactionModule\Entities\WithdrawalMethod;
use Modules\UserManagement\Entities\User;

class WithdrawRequest extends Model
{
    use HasFactory, HasUuid;

    protected $casts = [
        'amount' => 'float',
        'is_paid' => 'integer',
        'withdrawal_method_fields' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'request_updated_by',
        'amount',
        'request_status',
        'is_paid',
        'note',
        'admin_note',
        'withdrawal_method_id',
        'withdrawal_method_fields'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'user_id', 'user_id');
    }

    public function request_updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'request_updated_by');
    }

    public function withdraw_method(): BelongsTo
    {
        return $this->belongsTo(WithdrawalMethod::class, 'withdrawal_method_id');
    }

    protected static function newFactory()
    {
        return \Modules\ProviderManagement\Database\factories\WithdrawRequestFactory::new();
    }
}
                                                                                                             <?php

namespace Modules\ProviderManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\BookingModule\Entities\Booking;
use Modules\CategoryManagement\Entities\Category;
use Modules\ServiceManagement\Entities\Service;
use Modules\UserManagement\Entities\User;

class SubscribedService extends Model
{
    use HasFactory, HasUuid;

    protected $casts = [
        'is_subscribed' => 'integer'
    ];

    protected $fillable = ['provider_id', 'category_id', 'sub_category_id'];

    public function scopeOfStatus($query, $status)
    {
        $query->where('is_subscribed', $status);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sub_category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'sub_category_id', 'sub_category_id');
    }

    public function completed_booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'sub_category_id', 'sub_category_id')->where('booking_status', BOOKING_STATUSES[3]['key']);
    }

    protected function scopeOfSubscription($query, $status)
    {
        $query->where('is_subscribed', $status);
    }
}
