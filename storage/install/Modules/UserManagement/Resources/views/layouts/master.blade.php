<?php

namespace Modules\ReviewModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\BookingModule\Entities\Booking;
use Modules\ProviderManagement\Entities\Provider;
use Modules\ServiceManagement\Entities\Service;
use Modules\UserManagement\Entities\User;

class Review extends Model
{
    use HasFactory;
    use HasUuid;

    protected $casts = [
        'review_rating' => 'integer',
        'review_images' => 'array',
        'is_active' => 'integer',
    ];

    protected $fillable = [];

    public function booking(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function provider(): \Illuminate\Database\Eloquent\Relations\BelongsTo<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Module UserManagement</title>

       {{-- Laravel Mix - CSS File --}}
       {{-- <link rel="stylesheet" href="{{ mix('css/usermanagement.css') }}"> --}}

    </head>
    <body>
        <script>
            localStorage.theme && document.querySelector('body').setAttribute("theme", localStorage.theme);
            localStorage.dir && document.querySelector('html').setAttribute("dir", localStorage.dir);
        </script>
        @yield('content')

        {{-- Laravel Mix - JS File --}}
        {{-- <script src="{{ mix('js/usermanagement.js') }}"></script> --}}
    </body>
</html>
