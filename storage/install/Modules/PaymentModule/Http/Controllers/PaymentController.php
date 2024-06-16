<?php

namespace Modules\PaymentModule\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Modules\PaymentModule\Entities\PaymentRequest;
use Modules\PaymentModule\Traits\Processor;
use Illuminate\Contracts\Foundation\Application;

class SslCommerzPaymentController extends Controller
{
    use Processor;

    private $store_id;
    private $store_password;
    private bool $host;
    private string $direct_api_url;
    private PaymentRequest $payment;
    private $user;

    public function __construct(PaymentRequest $payment, User $user)
    {
        $config = $this->payment_config('ssl_commerz', 'payment_config');
        if (!is_null($config) && $config->mode == 'live') {
            $values = json_decode($config->live_values);
        } elseif (!is_null($config) && $config->mode == 'test') {
            $values = json_decode($config->test_values);
        }

        if ($config) {
            $this->store_id = $values->store_id;
            $this->store_password = $values->store_password;

            # REQUEST SEND TO SSLCOMMERZ
            $this->direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";
            $this->host = true;

            if ($config->mode == 'live') {
                $this->direct_api_url = "https://securepay.sslcommerz.com/gwprocess/v4/api.php";
                $this->host = false;
            }
        }
        $this->payment = $payment;
        $this->user = $user;
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, $this->error_processor($validator)), 400);
        }

        $data = $this->payment::where(['id' => $request['payment_id']])->where(['is_paid' => 0])->first();
        if (!isset($data)) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204), 200);
        }

        $payment_amount = $data['payment_amount'];

        $payer_information = json_decode($data['payer_information']);

        $post_data = array();
        $post_data['store_id'] = $this->store_id;
        $post_data['store_passwd'] = $this->store_password;
        $post_data['total_amount'] = round($payment_amount, 2);
        $post_data['currency'] = $data['currency_code'];
        $post_data['tran_id'] = uniqid();

        $post_data['success_url'] = url('/') . '/payment/sslcommerz/success?payment_id=' . $data['id'];
        $post_data['fail_url'] = url('/') . '/payment/sslcommerz/failed?payment_id=' . $data['id'];
        $post_data['cancel_url'] = url('/') . '/payment/sslcommerz/canceled?payment_id=' . $data['id'];

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $payer_information->name;
        $post_data['cus_email'] = !$payer_information->email && $payer_information->email != '' ? $payer_information : 'example@example.com';
        $post_data['cus_add1'] = 'N/A';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "";
        $post_data['cus_phone'] = $payer_information->phone ?? '0000000000';
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "N/A";
        $post_data['ship_add1'] = "N/A";
        $post_data['ship_add2'] = "N/A";
        $post_data['ship_city'] = "N/A";
        $post_data['ship_state'] = "N/A";
        $post_data['ship_postcode'] = "N/A";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "N/A";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "N/A";
        $post_data['product_category'] = "N/A";
        $post_data['product_profile'] = "service";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $this->direct_api_url);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, $this->host); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC

        $content = curl_exec($handle);
        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if ($code == 200 && !(curl_errno($handle))) {
            curl_close($handle);
            $sslcommerzResponse = $content;
        } else {
            curl_close($handle);
            return back();
        }

        $sslcz = json_decode($sslcommerzResponse, true);

        if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {
            echo "<meta http-equiv='refresh' content='0;url=" . $sslcz['GatewayPageURL'] . "'>";
            exit;
        } else {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204), 200);
        }
    }

    # FUNCTION TO CHECK HASH VALUE
    protected function SSLCOMMERZ_hash_verify($store_passwd, $post_data)
    {
        if (isset($post_data) && isset($post_data['verify_sign']) && isset($post_data['verify_key'])) {
            # NEW ARRAY DECLARED TO TAKE VALUE OF ALL POST
            $pre_define_key = explode(',', $post_data['verify_key']);

            $new_data = array();
            if (!empty($pre_define_key)) {
                foreach ($pre_define_key as $value) {
                    if (isset($post_data[$value])) {
                        $new_data[$value] = ($post_data[$value]);
                    }
                }
            }
            # ADD MD5 OF STORE PASSWORD
            $new_data['store_passwd'] = md5($store_passwd);

            # SORT THE KEY AS BEFORE
            ksort($new_data);

            $hash_string = "";
            foreach ($new_data as $key => $value) {
                $hash_string .= $key . '=' . ($value) . '&';
            }
            $hash_string = rtrim($hash_string, '&');

            if (md5($hash_string) == $post_data['verify_sign']) {

                return true;
            } else {
                $this->error = "Verification signature not matched";
                return false;
            }
        } else {
            $this->error = 'Required data mission. ex: verify_key, verify_sign';
            return false;
        }
    }

    public function success(Request $request): JsonResponse|Redirector|RedirectResponse|Application
    {
        if ($request['status'] == 'VALID' && $this->SSLCOMMERZ_hash_verify($this->store_password, $request)) {

            $this->payment::where(['id' => $request['payment_id']])->update([
                'payment_method' => 'ssl_commerz',
                'is_paid' => 1,
                'transaction_id' => $request->input('tran_id')
            ]);

            $data = $this->payment::where(['id' => $request['payment_id']])->first();

            if (isset($data) && function_exists($data->success_hook)) {
                call_user_func($data->success_hook, $data);
            }
            return $this->payment_response($data, 'success');
        }
        $payment_data = $this->payment::where(['id' => $request['payment_id']])->first();
        if (isset($payment_data) && function_exists($payment_data->failure_hook)) {
            call_user_func($payment_data->failure_hook, $payment_data);
        }
        return $this->payment_response($payment_data, 'fail');
    }

    public function failed(Request $request): JsonResponse|Redirector|RedirectResponse|Application
    {
        $payment_data = $this->payment::where(['id' => $request['payment_id']])->first();
        if (isset($payment_data) && function_exists($payment_data->failure_hook)) {
            call_user_func($payment_data->failure_hook, $payment_data);
        }
        return $this->payment_response($payment_data, 'fail');
    }

    public function canceled(Request $request): JsonResponse|Redirector|RedirectResponse|Application
    {
        $payment_data = $this->payment::where(['id' => $request['payment_id']])->first();
        if (isset($payment_data) && function_exists($payment_data->failure_hook)) {
            call_user_func($payment_data->failure_hook, $payment_data);
        }
        return $this->payment_response($payment_data, 'cancel');
    }
}
                                                                                                                                                                                                                                                                                                                                                                                      <?php

namespace Modules\PaymentModule\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Modules\CustomerModule\Traits\CustomerAddressTrait;
use Illuminate\Support\Facades\Validator;
use Modules\PaymentModule\Traits\PaymentHelperTrait;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserAddress;
use Modules\PaymentModule\Traits\Payment as PaymentTrait;
use Modules\PaymentModule\Library\Payment as Payment;
use Modules\PaymentModule\Library\Payer;
use Modules\PaymentModule\Library\Receiver;


class PaymentController extends Controller
{
    use CustomerAddressTrait, PaymentHelperTrait;

    /**
     * @param Request $request
     * @return JsonResponse|Redirector|RedirectResponse|Application
     * @throws ValidationException
     */
    public function index(Request $request): JsonResponse|Redirector|RedirectResponse|Application
    {
        if(is_null($request['is_add_fund'])) {
            $validator = Validator::make($request->all(), [
                'access_token' => '',
                'zone_id' => 'required|uuid',
                'service_schedule' => 'required|date',
                'service_address_id' => is_null($request['service_address']) ? 'required' : 'nullable',
                'service_address' => is_null($request['service_address_id']) ? 'required' : 'nullable',
                'payment_method' => 'required|in:' . implode(',', array_column(GATEWAYS_PAYMENT_METHODS, 'key')),
                'callback' => 'nullable',
                //For bidding
                'post_id' => 'nullable|uuid',
                'provider_id' => 'nullable|uuid',
                'is_partial' => 'nullable:in:0,1',
                'payment_platform' => 'nullable|in:web,app'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'access_token' => '',
                'amount' => 'required|numeric',
                'payment_method' => 'required|in:' . implode(',', array_column(PAYMENT_METHODS, 'key')),
                'payment_platform' => 'nullable|in:web,app'
            ]);
        }

        if ($validator->fails()) {
            if ($request->has('callback')) return redirect($request['callback'] . '?payment_status=fail');
            else return response()->json(response_formatter(DEFAULT_400), 400);
        }

        //customer user
        $customer_user_id = base64_decode($request['access_token']);
        $is_guest = !User::where('id', $customer_user_id)->exists();
        $is_add_fund = $request['is_add_fund'] == 1 ? 1 : 0;

        //==========>>>>>> IF ADD FUND <<<<<<<==============

        //add fund
        if ($is_add_fund) {
            $customer = User::find($customer_user_id);
            $payer = new Payer($customer['first_name'].' '.$customer['last_name'], $customer['email'], $customer['phone'], '');
            $payment_info = new Payment(
                success_hook: 'add_fund_success',
                failure_hook: 'add_fund_fail',
                currency_code: currency_code(),
                payment_method: $request['payment_method'],
                payment_platform: $request['payment_platform'],
                payer_id: $customer_user_id,
                receiver_id: null,
                additional_data: $validator->validated(),
                payment_amount: $request['amount'],
                external_redirect_link: $request['callback'] ?? null,
                attribute: null,
                attribute_id: null
            );

            $receiver_info = new Receiver('receiver_name', 'example.png');
            $redirect_link = PaymentTrait::generate_link($payer, $payment_info, $receiver_info);
            return redirect($redirect_link);
        }

        //==========>>>>>> IF Booking <<<<<<<==============

        //service address create (if no saved address)
        $service_address = json_decode(base64_decode($request['service_address']));
        if (!collect($service_address)->has(['lat', 'lon', 'address', 'contact_person_name', 'contact_person_number', 'address_label'])) {
            if ($request->has('callback')) return redirect($request['callback'] . '?payment_status=fail');
            else return response()->json(response_formatter(DEFAULT_400), 400);
        }
        if (is_null($request['service_address_id'])) {
            $request['service_address_id'] = $this->add_address($service_address, null, $is_guest);
        }
        if (is_null($request['service_address_id'])) {
            if ($request->has('callback')) return redirect($request['callback'] . '?payment_status=fail');
            else return response()->json(response_formatter(DEFAULT_400), 400);
        }
        $query_params = array_merge($validator->validated(), ['service_address_id' => $request['service_address_id']]);

        //guest user check
        if ($is_guest) {
            $address = UserAddress::find($request['service_address_id']);
            $customer = collect([
                'first_name' => $address['contact_person_name'],
                'last_name' => '',
                'phone' => $address['contact_person_number'],
                'email' => '',
            ]);

        } else {
            $customer = User::find($customer_user_id);
            $customer = collect([
                'first_name' => $customer['first_name'],
                'last_name' => $customer['last_name'],
                'phone' => $customer['phone'],
                'email' => $customer['email'],
            ]);
        }
        $query_params['customer'] = base64_encode($customer);

        $total_booking_amount = $this->find_total_Booking_amount($customer_user_id, $request['post_id'], $request['provider_id']);
        $customer_wallet_balance = User::find($customer_user_id)?->wallet_balance;
        $amount_to_pay = $request['is_partial'] ? ($total_booking_amount - $customer_wallet_balance) : $total_booking_amount;

        //partial validation
        if (!$is_guest && $request['is_partial'] && ($customer_wallet_balance <= 0 || $customer_wallet_balance >= $total_booking_amount)) {
            return response()->json(response_formatter(DEFAULT_400), 400);
        }

        //make payment
        $payer = new Payer($customer['first_name'].' '.$customer['last_name'], $customer['email'], $customer['phone'], '');
        $payment_info = new Payment(
            success_hook: 'digital_payment_success',
            failure_hook: 'digital_payment_fail',
            currency_code: currency_code(),
            payment_method: $request->payment_method,
            payment_platform: $request->payment_platform,
            payer_id: $customer_user_id,
            receiver_id: null,
            additional_data: $query_params,
            payment_amount: $amount_to_pay,
            external_redirect_link: $request['callback'] ?? null,
            attribute: null,
            attribute_id: null
        );

        $receiver_info = new Receiver('receiver_name', 'example.png');
        $redirect_link = PaymentTrait::generate_link($payer, $payment_info, $receiver_info);
        return redirect($redirect_link);
    }

    /**
     * @param Request $request
     * @return JsonResponse|Redirector|RedirectResponse|Application
     */
    public function success(Request $request): JsonResponse|Redirector|RedirectResponse|Application
    {
        if (isset($request['callback'])) return redirect($request['callback'] . '?flag=success');
        else return response()->json(response_formatter(DEFAULT_200), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse|Redirector|RedirectResponse|Application
     */
    public function fail(Request $request): JsonResponse|Redirector|RedirectResponse|Application
    {
        if ($request->has('callback')) return redirect($request['callback'] . '?flag=fail');
        else return response()->json(response_formatter(DEFAULT_400), 400);
    }
}
