<?php

namespace Modules\Auth\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use DebugBar\DataCollector\Renderable;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\ProviderManagement\Entities\Provider;
use Modules\UserManagement\Entities\Serviceman;
use Modules\UserManagement\Entities\User;
use Modules\ZoneManagement\Entities\Zone;

class RegisterController extends Controller
{
    protected Provider $provider;
    protected User $owner;
    protected $user;
    protected $serviceman;
    protected Zone $zone;

    public function __construct(Provider $provider, User $owner, User $user, Serviceman $serviceman, Zone $zone)
    {
        $this->provider = $provider;
        $this->owner = $owner;
        $this->user = $user;
        $this->serviceman = $serviceman;
        $this->zone = $zone;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function customer_register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users',
            'password' => 'required|min:8',
            'gender' => 'in:male,female,others',
            'confirm_password' => 'required|same:password',
            'profile_image' =>  'image|mimes:jpeg,jpg,png,gif|max:10000',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 403);
        }

        $user = $this->user;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->profile_image = $request->has('profile_image') ? file_uploader('user/profile_image/', 'png', $request->profile_image) : 'default.png';
        $user->date_of_birth = $request->date_of_birth;
        $user->gender = $request->gender??'male';
        $user->password = bcrypt($request->password);
        $user->user_type = 'customer';
        $user->is_active = 1;
        $user->save();

        return response()->json(response_formatter(REGISTRATION_200), 200);
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function provider_self_register_form(Request $request): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $zones = $this->zone->get();
        return view('auth::provider-register', compact('zones'));
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function provider_self_register(Request $request): RedirectResponse
    {
        $request->validate([
            'contact_person_name' => 'required',
            'contact_person_phone' => 'required',
            'contact_person_email' => 'required',

            'account_first_name' => 'required',
            'account_last_name' => 'required',
            'zone_id' => 'required|uuid',
            'account_email' => 'required|email|unique:users,email',
            'account_phone' => 'required|unique:users,phone',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',

            'company_name' => 'required',
            'company_phone' => 'required|unique:providers',
            'company_address' => 'required',
            'company_email' => 'required|email|unique:providers',
            'logo' => 'required|image|mimes:jpeg,jpg,png,gif|max:10000',

            'identity_type' => 'required|in:passport,driving_licence,nid,trade_license,company_id',
            'identity_number' => 'required',
            'identity_images' => 'required|array',
            'identity_images.*' => 'image|mimes:jpeg,jpg,png,gif|max:2048',

            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $identity_images = [];
        foreach ($request->identity_images as $image) {
            $identity_images[] = file_uploader('provider/identity/', 'png', $image);
        }

        $provider = $this->provider;
        $provider->company_name = $request->company_name;
        $provider->company_phone = $request->company_phone;
        $provider->company_email = $request->company_email;
        $provider->logo = file_uploader('provider/logo/', 'png', $request->file('logo'));
        $provider->company_address = $request->company_address;

        $provider->contact_person_name = $request->contact_person_name;
        $provider->contact_person_phone = $request->contact_person_phone;
        $provider->contact_person_email = $request->contact_person_email;
        $provider->is_approved = 2;
        $provider->is_active = 0;
        $provider->zone_id = $request['zone_id'];
        $provider->coordinates = ['latitude' => $request['latitude'], 'longitude' => $request['longitude']];


        $owner = $this->owner;
        $owner->first_name = $request->account_first_name;
        $owner->last_name = $request->account_last_name;
        $owner->email = $request->account_email;
        $owner->phone = $request->account_phone;
        $owner->identification_number = $request->identity_number;
        $owner->identification_type = $request->identity_type;
        $owner->identification_image = $identity_images;
        $owner->password = bcrypt($request->password);
        $owner->user_type = 'provider-admin';
        $owner->is_active = 0;

        DB::transaction(function () use ($provider, $owner, $request) {
            $owner->save();
            $provider->user_id = $owner->id;
            $provider->save();
        });

        Toastr::success(PROVIDER_REGISTERED_200['message']);
        return redirect(route('provider.auth.verification.index'));
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function provider_register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'contact_person_name' => 'required',
            'contact_person_phone' => 'required',
            'contact_person_email' => 'required',

            'account_first_name' => 'required',
            'account_last_name' => 'required',
            'zone_id' => 'required|uuid',
            'account_email' => 'required|email|unique:users,email',
            'account_phone' => 'required|unique:users,phone',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',

            'company_name' => 'required',
            'company_phone' => 'required|unique:providers',
            'company_address' => 'required',
            'company_email' => 'required|email|unique:providers',
            'logo' => 'required|image|mimes:jpeg,jpg,png,gif|max:10000',

            'identity_type' => 'required|in:passport,driving_licence,nid,trade_license,company_id',
            'identity_number' => 'required',
            'identity_images' => 'required|array',
            'identity_images.*' => 'image|mimes:jpeg,jpg,png,gif',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $identity_images = [];
        foreach ($request->identity_images as $image) {
            $identity_images[] = file_uploader('provider/identity/', 'png', $image);
        }

        $provider = $this->provider;
        $provider->company_name = $request->company_name;
        $provider->company_phone = $request->company_phone;
        $provider->company_email = $request->company_email;
        $provider->logo = file_uploader('provider/logo/', 'png', $request->file('logo'));
        $provider->company_address = $request->company_address;

        $provider->contact_person_name = $request->contact_person_name;
        $provider->contact_person_phone = $request->contact_person_phone;
        $provider->contact_person_email = $request->contact_person_email;
        $provider->is_approved = 2;
        $provider->is_active = 0;
        $provider->zone_id = $request['zone_id'];

        $owner = $this->owner;
        $owner->first_name = $request->account_first_name;
        $owner->last_name = $request->account_last_name;
        $owner->email = $request->account_email;
        $owner->phone = $request->account_phone;
        $owner->identification_number = $request->identity_number;
        $owner->identification_type = $request->identity_type;
        $owner->identification_image = $identity_images;
        $owner->password = bcrypt($request->password);
        $owner->user_type = 'provider-admin';
        $owner->is_active = 0;

        DB::transaction(function () use ($provider, $owner, $request) {
            $owner->save();
            $provider->user_id = $owner->id;
            $provider->save();
        });

        return response()->json(response_formatter(PROVIDER_STORE_200), 200);
    }


    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function user_verification(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'identity' => 'required',
            'otp' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $data = DB::table('user_verifications')
            ->where('identity', $request['identity'])
            ->where(['otp' => $request['otp']])->first();

        if (isset($data)) {
            $this->user->whereIn('user_type', CUSTOMER_USER_TYPES)
                ->where('phone', $request['identity'])
                ->update([
                    'is_phone_verified' => 1
                ]);

            DB::table('user_verifications')
                ->where('identity', $request['identity'])
                ->where(['otp' => $request['otp']])->delete();

            return response()->json(response_formatter(DEFAULT_VERIFIED_200), 200);
        }

        return response()->json(response_formatter(DEFAULT_404), 200);
    }

}
                                                                                                                                                                                                                                                                                                                                                                                                                <?php

namespace Modules\Auth\Http\Controllers\Web;

use Brian2694\Toastr\Facades\Toastr;
use Carbon\CarbonInterval;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\SMSModule\Lib\SMS_gateway;
use Modules\UserManagement\Emails\OTPMail;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserVerification;
use Modules\PaymentModule\Traits\SmsGateway;

class VerificationController extends Controller
{
    public function __construct(
        private User $user,
        private UserVerification $user_verification
    )
    {
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('auth::verification.send-otp');
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse|Renderable
     * @throws ValidationException
     */
    public function send_otp(Request $request): RedirectResponse|Renderable
    {
        Validator::make($request->all(), [
            'identity' => 'required|max:255',
            'identity_type' => 'required|in:phone,email'
        ])->validate();

        //provider check
        $user = $this->user->where($request['identity_type'], $request['identity'])->whereIn('user_type', PROVIDER_USER_TYPES)->first();
        if(!isset($user)) {
            Toastr::error(DEFAULT_404['message']);
            return redirect(route('provider.auth.verification.index'));
        }

        //resend time check
        $user_verification = $this->user_verification->where('identity', $request['identity'])->first();
        $otp_resend_time = business_config('otp_resend_time', 'otp_login_setup')?->live_values;


        if (isset($user_verification) && Carbon::parse($user_verification->updated_at)->DiffInSeconds() < $otp_resend_time) {
            $time = $otp_resend_time - Carbon::parse($user_verification->updated_at)->DiffInSeconds();
            Toastr::error(translate('Please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
            return redirect(route('provider.auth.verification.index'));
        }

        $otp = env('APP_ENV') != 'live' ? '1234' : rand(1000, 9999);
        $this->user_verification->updateOrCreate([
            'identity' => $request['identity'],
            'identity_type' => $request['identity_type']
        ], [
            'identity' => $request['identity'],
            'identity_type' => $request['identity_type'],
            'user_id' => null,
            'otp' => $otp,
            'expires_at' => now()->addMinute(3),
        ]);

        //send otp
        if ($request['identity_type'] == 'phone') {
            //for payment and sms gateway addon
            $published_status = 0;
            $payment_published_status = config('get_payment_publish_status');
            if (isset($payment_published_status[0]['is_published'])) {
                $published_status = $payment_published_status[0]['is_published'];
            }

            if($published_status == 1){
                $response = SmsGateway::send($request['identity'], $otp);
            }else{
                $response = SMS_gateway::send($request['identity'], $otp);
            }

        } else if ($request['identity_type'] == 'email') {
            try {
                Mail::to($request['identity'])->send(new OTPMail($otp));
                $response = 'success';
            } catch (\Exception $exception) {
                $response = 'error';
            }
        } else {
            $response = 'error';
        }

        if ($response == 'success') {
            Session::put('identity', $request['identity']);
            Session::put('identity_type', $request['identity_type']);

            Toastr::success(DEFAULT_SENT_OTP_200['message']);
            return view('auth::verification.verify-otp');

        } else {
            Toastr::error(DEFAULT_SENT_OTP_FAILED_200['message']);
            return redirect(route('provider.auth.verification.index'));
        }
    }

    public function verify_otp(Request $request): JsonResponse|Renderable|RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'identity' => 'required',
            'identity_type' => 'required',
            'otp' => 'required|max:4'
        ]);

        if ($validator->fails()) {
            $error = error_processor($validator);
            $message = $error[0]['message'] ?? DEFAULT_400['message'];
            Toastr::error($message);
            return redirect(route('provider.auth.verification.index'));
        }

        $max_otp_hit = business_config('maximum_otp_hit', 'otp_login_setup')->test_values ?? 5;
        $max_otp_hit_time = business_config('otp_resend_time', 'otp_login_setup')->test_values ?? 60;// seconds
        $temp_block_time = business_config('temporary_otp_block_time', 'otp_login_setup')->test_values ?? 600; // seconds

        $verify = $this->user_verification->where(['identity' => $request['identity'], 'otp' => $request['otp']])->first();

        if (isset($verify)) {
            if (isset($verify->temp_block_time) && Carbon::parse($verify->temp_block_time)->DiffInSeconds() <= $temp_block_time) {
                $time = $temp_block_time - Carbon::parse($verify->temp_block_time)->DiffInSeconds();
                Toastr::success(translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
                return redirect(route('provider.auth.verification.index'));
            }

            if ($request['identity_type'] == 'email') {
                $user = $this->user->where('email', $request['identity'])->first();
                $user->is_email_verified = 1;
                $user->save();

            } else if ($request['identity_type'] == 'phone') {
                $user = $this->user->where('phone', $request['identity'])->first();
                $user->is_phone_verified = 1;
                $user->save();
            }
            $this->user_verification->where(['identity' => $request['identity'], 'otp' => $request['otp']])->delete();

            Toastr::success(OTP_VERIFICATION_SUCCESS_200['message']);
            return redirect(route('provider.auth.login'));

        } else {
            $verification_data = $this->user_verification->where('identity', $request['identity'])->first();

            if (isset($verification_data)) {
                if (isset($verification_data->temp_block_time) && Carbon::parse($verification_data->temp_block_time)->DiffInSeconds() <= $temp_block_time) {
                    $time = $temp_block_time - Carbon::parse($verification_data->temp_block_time)->DiffInSeconds();
                    Toastr::error(translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
                    return redirect(route('provider.auth.verification.index'));
                }

                if ($verification_data->is_temp_blocked == 1 && Carbon::parse($verification_data->updated_at)->DiffInSeconds() >= $max_otp_hit_time) {

                    $user_verify = $this->user_verification->where(['identity' => $request['identity']])->first();
                    if (!isset($user_verify)) {
                        $user_verify = $this->user_verification;
                    }
                    $user_verify->hit_count = 0;
                    $user_verify->is_temp_blocked = 0;
                    $user_verify->temp_block_time = null;
                    $user_verify->save();
                }

                if ($verification_data->hit_count >= $max_otp_hit && Carbon::parse($verification_data->updated_at)->DiffInSeconds() < $max_otp_hit_time && $verification_data->is_temp_blocked == 0) {

                    $user_verify = $this->user_verification->where(['identity' => $request['identity']])->first();
                    if (!isset($user_verify)) {
                        $user_verify = $this->user_verification;
                    }
                    $user_verify->is_temp_blocked = 1;
                    $user_verify->temp_block_time = now();
                    $user_verify->save();

                    $time = $temp_block_time - Carbon::parse($verification_data->temp_block_time)->DiffInSeconds();
                    Toastr::error(translate('Too_many_attempts. please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
                    return redirect(route('provider.auth.verification.index'));
                }
            }

            $user_verify = $this->user_verification->where(['identity' => $request['identity']])->first();
            if (!isset($user_verify)) {
                $user_verify = $this->user_verification;
            }
            $user_verify->hit_count += 1;
            $user_verify->temp_block_time = null;
            $user_verify->save();
        }

        Toastr::error(OTP_VERIFICATION_FAIL_403['message']);
        return redirect(route('provider.auth.verification.index'));
    }
}
