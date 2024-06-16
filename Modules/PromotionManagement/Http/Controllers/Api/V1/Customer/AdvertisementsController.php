<?php

namespace Modules\PromotionManagement\Http\Controllers\Api\V1\Customer;


use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Modules\PromotionManagement\Entities\Advertisement;

class AdvertisementsController extends Controller
{

    public function __construct(
        private Advertisement $advertisement,
    )
    {}

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function AdsList(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $advertisements = $this->advertisement->with(['attachments', 'provider', 'provider.owner', 'provider.subscribed_services.sub_category'=>function($query){
            $query->withoutGlobalScopes();
        }])
            ->orderByRaw('ISNULL(priority), priority')
            ->whereIn('status', ['approved', 'resumed'])->where('start_date', '<=', Carbon::today())->where('end_date', '>=', Carbon::today())
            ->whereHas('provider', function ($query) {
                $query->where('zone_id', Config::get('zone_id'));
            })
            ->latest()
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        foreach($advertisements as $advertisement){
            $advertisement->promotional_video = $advertisement?->attachment?->file_name;
            $advertisement->provider_cover_image = $advertisement?->attachments->where('type', 'provider_cover_image')->first()?->file_name;
            $advertisement->provider_profile_image = $advertisement?->attachments->where('type', 'provider_profile_image')->first()?->file_name;
            $advertisement->provider_review = $advertisement?->review?->value;
            $advertisement->provider_rating = $advertisement?->rating?->value;

            unset($advertisement->attachments, $advertisement->attachment, $advertisement->review, $advertisement->rating);
        }

        return response()->json(response_formatter(DEFAULT_200, $advertisements), 200);
    }

}
