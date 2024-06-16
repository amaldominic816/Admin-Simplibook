<?php
namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ActivationClass
{
   public function dmvf(Request $request)
    {
		session()->put('purchase_key', $request['purchase_key']);//pk
		session()->put('username', $request['username']);//un
		return redirect()->route('step3');//s3
    }

    public function actch()
    {
		return response()->json([
			'active' => 1
		]);
    }

    public function is_local()
    {
		return true;
    }
}
