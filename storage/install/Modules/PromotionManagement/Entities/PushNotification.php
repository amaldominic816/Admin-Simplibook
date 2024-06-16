<?php

namespace Modules\PromotionManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discount extends Model
{
    use HasFactory, HasUuid;

    protected $casts = [
        'discount_amount' => 'float',
        'min_purchase' => 'float',
        'max_discount_amount' => 'float',
        'limit_per_user' => 'integer',
        'is_active' => 'integer',
    ];

    protected $fillable = [];

    public function discount_types(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMa<?php

namespace Modules\PromotionManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PushNotification extends Model
{
    use HasFactory;
    use HasUuid;

    protected $casts = [
        'zone_ids' => 'array',
        'to_users' => 'array',
        'is_active' => 'integer',
    ];

    protected $fillable = ['id', 'title', 'description', 'to_users', 'zone_ids', 'cover_image', 'is_active'];

    public function scopeOfStatus($query, $status)
    {
        $query->where('is_active', '=', $status);
    }
}
