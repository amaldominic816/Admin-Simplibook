// <?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Http;
// class OtpController extends Controller
// {
//      public function sendOtp(Request $request)
//     {
//         // Extract data from request
//         $templateId = $request->input('template_id');
//         $mobile = $request->input('mobile');
//         $authKey = $request->input('auth_key');

//         // Set up the request payload
//         $payload = [
//             'Param1' => 'value1',
//             'Param2' => 'value2',
//             'Param3' => 'value3'
//         ];

//         // Make the HTTP POST request
//         $response = Http::post('https://control.msg91.com/api/v5/otp', [
//             'template_id' => "661f4822d6fc0546335e0913",
//             'mobile' => $mobile,
//             'authkey' => "412412A1MHRlIEgtV661f5734P1",
//             'json' => $payload // Sending data as JSON
//         ]);

//         // Check if there was an error in the request
//         if ($response->failed()) {
//             return response()->json(['error' => 'Failed to send OTP'], 500);
//         }

//         // Get the response body
//         $responseData = $response->json();

//         // Return the response
//         return response()->json($responseData);
//     }
// }
