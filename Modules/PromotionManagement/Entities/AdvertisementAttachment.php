<?php

namespace Modules\PromotionManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvertisementAttachment extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'advertisement_id',
        'file_extension_type',
        'file_name',
        'type'
    ];

}
