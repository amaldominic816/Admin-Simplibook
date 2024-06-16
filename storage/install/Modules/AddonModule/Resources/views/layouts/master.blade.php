<div class="modal-header border-0 pb-0 d-flex justify-content-end">
    <button
        type="button"
        class="btn-close border-0"
        data-bs-dismiss="modal"
        aria-label="Close"
    ><i class="tio-clear"></i></button>
</div>
<div class="modal-body px-4 px-sm-5">
    <div class="mb-4 text-center">
        @php($logo=\Modules\BusinessSettingsModule\Entities\BusinessSettings::where('key_name','business_logo')->first()->value)
        <img
            width="200"
            src="{{asset('storage/app/public/restaurant/'.$logo)}}"
            <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Module AddonModule</title>

       {{-- Laravel Mix - CSS File --}}
       {{-- <link rel="stylesheet" href="{{ mix('css/addonmodule.css') }}"> --}}

    </head>
    <body>
        @yield('content')

        {{-- Laravel Mix - JS File --}}
        {{-- <script src="{{ mix('js/addonmodule.js') }}"></script> --}}
    </body>
</html>
