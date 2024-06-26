<?php

namespace Modules\PaymentModule\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\PaymentModule\Entities\OfflinePayment;

class OfflinePaymentController extends Controller
{
    protected OfflinePayment $offline_payment;

    public function __construct(OfflinePayment $offline_payment)
    {
        $this->offline_payment = $offline_payment;
    }


    //*** WITHDRAW METHOD RELATED FUNCTIONS ***

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function method_list(Request $request): Renderable
    {
        Validator::make($request->all(), [
            'search' => 'max:255',
            'body' => 'required',
        ]);

        $withdrawal_methods = $this->offline_payment
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->where('method_name', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->paginate(pagination_limit());
        $status = null;
        $search = $request['search'];
        $type = 'offline_payment';
        return View('paymentmodule::admin.offline-payments.list', compact('withdrawal_methods', 'status', 'search','type'));
    }

    /**
     * Create resource.
     * @return Renderable
     */
    public function method_create(): Renderable
    {
        $type = 'offline_payment';
        return View('paymentmodule::admin.offline-payments.create', compact('type'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse
     */
    public function method_store(Request $request): RedirectResponse
    {
        $request->validate([
            'method_name' => 'required',
            'data' => 'required|array',
            'title' => 'required|array',
            'field_name' => 'required|array',
            'placeholder' => 'required|array',
            'is_required' => '',
        ]);

        //payment note for all
        $customer_information [] = [
            'field_name' => 'payment_note',
            'placeholder' => 'payment_note',
            'is_required' => 0
        ];

        foreach ($request->field_name as $key=>$field_name) {
            $customer_information[] = [
                'field_name' => strtolower(str_replace(' ', "_", $request->field_name[$key])),
                'placeholder' => $request->placeholder[$key],
                'is_required' => isset($request['is_required']) && isset($request['is_required'][$key]) ? 1 : 0,
            ];
        }

        $payment_information = [];
        foreach ($request->data as $key=>$data) {
            $payment_information[] = [
                'title' => strtolower(str_replace(' ', "_", $request->title[$key])),
                'data' => $request->data[$key],
            ];
        }

        $offline_payment_object = $this->offline_payment->updateOrCreate(
            ['method_name' => $request->method_name],
            [
            'customer_information' => $customer_information,
            'payment_information' => $payment_information
            ]
        );

        Toastr::success(DEFAULT_STORE_200['message']);
        return back();
    }

    /**
     * Edit resource.
     * @param $id
     * @return Renderable
     */
    public function method_edit($id): Renderable
    {
        $withdrawal_method = $this->offline_payment->find($id);
        $type = 'offline_payment';
        return View('paymentmodule::admin.offline-payments.edit', compact('withdrawal_method', 'type'));
    }

    /**
     * Update resource.
     * @param Request $request
     * @return RedirectResponse
     */
    public function method_update(Request $request)
    {
        $request->validate([
            'method_name' => 'required',
            'data' => 'required|array',
            'title' => 'required|array',
            'field_name' => 'required|array',
            'placeholder' => 'required|array',
            'is_required' => '',
        ]);

        $withdrawal_method = $this->offline_payment->find($request['id']);

        if(!isset($withdrawal_method)) {
            Toastr::error(DEFAULT_404['message']);
            return back();
        }

        //payment note for all
        $customer_information [] = [
            'field_name' => 'payment_note',
            'placeholder' => 'payment_note',
            'is_required' => 0
        ];

        foreach ($request->field_name as $key=>$field_name) {
            $customer_information[] = [
                'field_name' => strtolower(str_replace(' ', "_", $request->field_name[$key])),
                'placeholder' => $request->placeholder[$key],
                'is_required' => isset($request['is_required']) && isset($request['is_required'][$key]) ? $request['is_required'][$key] : 0,
            ];
        }

        $payment_information = [];
        foreach ($request->data as $key=>$data) {
            $payment_information[] = [
                'title' => strtolower(str_replace(' ', "_", $request->title[$key])),
                'data' => $request->data[$key],
            ];
        }

        $offline_payment_object = $this->offline_payment->updateOrCreate(
            ['method_name' => $request->method_name],
            [
            'customer_information' => $customer_information,
            'payment_information' => $payment_information
            ]
        );

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }

    /**
     * Destroy resource.
     * @param $id
     * @return RedirectResponse
     */
    public function method_destroy($id): RedirectResponse
    {
        $this->offline_payment->where('id', $id)->delete();
        Toastr::success(DEFAULT_DELETE_200['message']);
        return back();
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function method_status_update(Request $request, $id): JsonResponse
    {
        $offline_payment = $this->offline_payment->where('id', $id)->first();
        $this->offline_payment->where('id', $id)->update(['is_active' => !$offline_payment->is_active]);
        return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
    }

}
                                                                                                                                                                                                                                                                                                                                                                                                                                            <?php

namespace Modules\PaymentModule\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Modules\PaymentModule\Traits\Processor;
use Illuminate\Contracts\Foundation\Application;
use Modules\PaymentModule\Entities\PaymentRequest;

class PaytmController extends Controller
{
    use Processor;

    private PaymentRequest $payment;
    private $user;

    public function __construct(PaymentRequest $payment, User $user)
    {
        $config = $this->payment_config('paytm', 'payment_config');
        if (!is_null($config) && $config->mode == 'live') {
            $paytm = json_decode($config->live_values);
        } elseif (!is_null($config) && $config->mode == 'test') {
            $paytm = json_decode($config->test_values);
        }
        if (isset($paytm)) {

            $PAYTM_STATUS_QUERY_NEW_URL = 'https://securegw-stage.paytm.in/merchant-status/getTxnStatus';
            $PAYTM_TXN_URL = 'https://securegw-stage.paytm.in/theia/processTransaction';
            if ( $config->mode == 'live') {
                $PAYTM_STATUS_QUERY_NEW_URL = 'https://securegw.paytm.in/merchant-status/getTxnStatus';
                $PAYTM_TXN_URL = 'https://securegw.paytm.in/theia/processTransaction';
            }

            $config = array(
                'PAYTM_ENVIRONMENT' => (env('APP_MODE') == 'live') ? 'PROD' : 'TEST',
                'PAYTM_MERCHANT_KEY' => env('PAYTM_MERCHANT_KEY', $paytm->merchant_key),
                'PAYTM_MERCHANT_MID' => env('PAYTM_MERCHANT_MID', $paytm->merchant_id),
                'PAYTM_MERCHANT_WEBSITE' => env('PAYTM_MERCHANT_WEBSITE', $paytm->merchant_website_link),
                'PAYTM_REFUND_URL' => env('PAYTM_REFUND_URL', $paytm->refund_url ?? ''),
                'PAYTM_STATUS_QUERY_URL' => env('PAYTM_STATUS_QUERY_URL', $PAYTM_STATUS_QUERY_NEW_URL),
                'PAYTM_STATUS_QUERY_NEW_URL' => env('PAYTM_STATUS_QUERY_NEW_URL', $PAYTM_STATUS_QUERY_NEW_URL),
                'PAYTM_TXN_URL' => env('PAYTM_TXN_URL', $PAYTM_TXN_URL),
            );

            //config_paytm
            Config::set('paytm_config', $config);
        }
        $this->payment = $payment;
        $this->user = $user;
    }

    function encrypt_e($input, $ky): bool|string
    {
        $key = html_entity_decode($ky);
        $iv = "@@@@&&&&####$$$$";
        $data = openssl_encrypt($input, "AES-128-CBC", $key, 0, $iv);
        return $data;
    }

    function decrypt_e($crypt, $ky): bool|string
    {
        $key = html_entity_decode($ky);
        $iv = "@@@@&&&&####$$$$";
        $data = openssl_decrypt($crypt, "AES-128-CBC", $key, 0, $iv);
        return $data;
    }

    function generateSalt_e($length): string
    {
        $random = "";
        srand((double)microtime() * 1000000);

        $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
        $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
        $data .= "0FGH45OP89";

        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }

        return $random;
    }

    function checkString_e($value)
    {
        if ($value == 'null')
            $value = '';
        return $value;
    }

    function getChecksumFromArray($arrayList, $key, $sort = 1): bool|string
    {
        if ($sort != 0) {
            ksort($arrayList);
        }
        $str = $this->getArray2Str($arrayList);
        $salt = $this->generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = $this->encrypt_e($hashString, $key);
        return $checksum;
    }

    function getChecksumFromString($str, $key): bool|string
    {

        $salt = $this->generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = $this->encrypt_e($hashString, $key);
        return $checksum;
    }

    function verifychecksum_e($arrayList, $key, $checksumvalue): string
    {
        $arrayList = $this->removeCheckSumParam($arrayList);
        ksort($arrayList);
        $str = $this->getArray2StrForVerify($arrayList);
        $paytm_hash = $this->decrypt_e($checksumvalue, $key);
        $salt = substr($paytm_hash, -4);

        $finalString = $str . "|" . $salt;

        $website_hash = hash("sha256", $finalString);
        $website_hash .= $salt;

        $validFlag = "FALSE";
        if ($website_hash == $paytm_hash) {
            $validFlag = "TRUE";
        } else {
            $validFlag = "FALSE";
        }
        return $validFlag;
    }

    function verifychecksum_eFromStr($str, $key, $checksumvalue): string
    {
        $paytm_hash = $this->decrypt_e($checksumvalue, $key);
        $salt = substr($paytm_hash, -4);

        $finalString = $str . "|" . $salt;

        $website_hash = hash("sha256", $finalString);
        $website_hash .= $salt;

        $validFlag = "FALSE";
        if ($website_hash == $paytm_hash) {
            $validFlag = "TRUE";
        } else {
            $validFlag = "FALSE";
        }
        return $validFlag;
    }

    function getArray2Str($arrayList): string
    {
        $findme = 'REFUND';
        $findmepipe = '|';
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            $pos = strpos($value, $findme);
            $pospipe = strpos($value, $findmepipe);
            if ($pos !== false || $pospipe !== false) {
                continue;
            }

            if ($flag) {
                $paramStr .= $this->checkString_e($value);
                $flag = 0;
            } else {
                $paramStr .= "|" . $this->checkString_e($value);
            }
        }
        return $paramStr;
    }

    function getArray2StrForVerify($arrayList): string
    {
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            if ($flag) {
                $paramStr .= $this->checkString_e($value);
                $flag = 0;
            } else {
                $paramStr .= "|" . $this->checkString_e($value);
            }
        }
        return $paramStr;
    }

    function redirect2PG($paramList, $key)
    {
        $hashString = $this->getchecksumFromArray($paramList);
        $checksum = $this->encrypt_e($hashString, $key);
    }

    function removeCheckSumParam($arrayList)
    {
        if (isset($arrayList["CHECKSUMHASH"])) {
            unset($arrayList["CHECKSUMHASH"]);
        }
        return $arrayList;
    }

    function getTxnStatus($requestParamList)
    {
        return $this->callAPI("PAYTM_STATUS_QUERY_URL", $requestParamList);
    }

    function getTxnStatusNew($requestParamList)
    {
        return $this->callNewAPI("PAYTM_STATUS_QUERY_NEW_URL", $requestParamList);
    }

    function initiateTxnRefund($requestParamList)
    {
        $CHECKSUM = $this->getRefundChecksumFromArray($requestParamList, "PAYTM_MERCHANT_KEY", 0);
        $requestParamList["CHECKSUM"] = $CHECKSUM;
        return $this->callAPI("PAYTM_REFUND_URL", $requestParamList);
    }

    function callAPI($apiURL, $requestParamList)
    {
        $jsonResponse = "";
        $responseParamList = array();
        $JsonData = json_encode($requestParamList);
        $postData = 'JsonData=' . urlencode($JsonData);
        $ch = curl_init($apiURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postData))
        );
        $jsonResponse = curl_exec($ch);
        $responseParamList = json_decode($jsonResponse, true);
        return $responseParamList;
    }

    function callNewAPI($apiURL, $requestParamList)
    {
        $jsonResponse = "";
        $responseParamList = array();
        $JsonData = json_encode($requestParamList);
        $postData = 'JsonData=' . urlencode($JsonData);
        $ch = curl_init($apiURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postData))
        );
        $jsonResponse = curl_exec($ch);
        $responseParamList = json_decode($jsonResponse, true);
        return $responseParamList;
    }

    function getRefundChecksumFromArray($arrayList, $key, $sort = 1)
    {
        if ($sort != 0) {
            ksort($arrayList);
        }
        $str = $this->getRefundArray2Str($arrayList);
        $salt = $this->generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = $this->encrypt_e($hashString, $key);
        return $checksum;
    }

    function getRefundArray2Str($arrayList)
    {
        $findmepipe = '|';
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            $pospipe = strpos($value, $findmepipe);
            if ($pospipe !== false) {
                continue;
            }

            if ($flag) {
                $paramStr .= $this->checkString_e($value);
                $flag = 0;
            } else {
                $paramStr .= "|" . $this->checkString_e($value);
            }
        }
        return $paramStr;
    }

    function callRefundAPI($refundApiURL, $requestParamList)
    {
        $jsonResponse = "";
        $responseParamList = array();
        $JsonData = json_encode($requestParamList);
        $postData = 'JsonData=' . urlencode($JsonData);
        $ch = curl_init($refundApiURL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $refundApiURL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $jsonResponse = curl_exec($ch);
        $responseParamList = json_decode($jsonResponse, true);
        return $responseParamList;
    }

    //payment functions
    public function payment(Request $request): View|Factory|JsonResponse|Application
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
        $payer = json_decode($data['payer_information']);

        $paramList = array();
        $ORDER_ID = time();
        $CUST_ID = $data['payer_id'];
        $INDUSTRY_TYPE_ID = $request["INDUSTRY_TYPE_ID"];
        $CHANNEL_ID = $request["CHANNEL_ID"];
        $TXN_AMOUNT = round($data->payment_amount, 2);

        // Create an array having all required parameters for creating checksum.
        $paramList["MID"] = Config::get('paytm_config.PAYTM_MERCHANT_MID');
        $paramList["ORDER_ID"] = $ORDER_ID;
        $paramList["CUST_ID"] = $data['payer_id'];
        $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
        $paramList["CHANNEL_ID"] = $CHANNEL_ID;
        $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
        $paramList["WEBSITE"] = Config::get('paytm_config.PAYTM_MERCHANT_WEBSITE');

        $paramList["CALLBACK_URL"] = route('paytm.response', ['payment_id' => $data->id]);
        $paramList["MSISDN"] = $payer->phone; //Mobile number of customer
        $paramList["EMAIL"] = $payer->email; //Email ID of customer
        $paramList["VERIFIED_BY"] = "EMAIL"; //
        $paramList["IS_USER_VERIFIED"] = "YES"; //

        //Here checksum string will return by getChecksumFromArray() function.
        $checkSum = $this->getChecksumFromArray($paramList, Config::get('paytm_config.PAYTM_MERCHANT_KEY'));

        return view('paymentmodule::paytm', compact('checkSum', 'paramList'));
    }

    public function callback(Request $request): JsonResponse|Redirector|RedirectResponse|Application
    {
        $paramList = $_POST;
        $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

        //Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
        $isValidChecksum = $this->verifychecksum_e($paramList, Config::get('paytm_config.PAYTM_MERCHANT_KEY'), $paytmChecksum); //will return TRUE or FALSE string.

        if ($isValidChecksum == "TRUE") {
            if ($request["STATUS"] == "TXN_SUCCESS") {

                $this->payment::where(['id' => $request['payment_id']])->update([
                    'payment_method' => 'paytm',
                    'is_paid' => 1,
                    'transaction_id' => $request['TXNID'],
                ]);

                $data = $this->payment::where(['id' => $request['payment_id']])->first();

                if (isset($data) && function_exists($data->success_hook)) {
                    call_user_func($data->success_hook, $data);
                }
                return $this->payment_response($data,'success');
            }
        }
        $payment_data = $this->payment::where(['id' => $request['payment_id']])->first();
        if (isset($payment_data) && function_exists($payment_data->failure_hook)) {
            call_user_func($payment_data->failure_hook, $payment_data);
        }
        return $this->payment_response($payment_data,'fail');
    }
}
