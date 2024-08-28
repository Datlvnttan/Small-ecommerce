<?php

namespace Modules\User\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Requests\AddressRequest;
use Modules\User\Http\Requests\DeliveryAddressRequest;
use Modules\User\Services\DeliveryAddressService;

class DeliveryAddressApiController extends AddressApiController
{

    protected $deliveryAddressService;
    public function __construct(DeliveryAddressService $deliveryAddressService)
    {
        // $this->deliveryAddressService = $deliveryAddressService;
        parent::__construct($deliveryAddressService);
    }
    protected function typeCastingRequest(AddressRequest $request) : null|DeliveryAddressRequest
    {
        
        if ($request instanceof DeliveryAddressRequest) {
            /** @var DeliveryAddressRequest $deliveryAddressRequest */
            $deliveryAddressRequest = $request;
            return $deliveryAddressRequest;
        }
        return $request;
    }

        /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(DeliveryAddressRequest $req)
    {
        return $this->baseStore($req);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(DeliveryAddressRequest $req, $id)
    {
        return $this->baseUpdate($req, $id);
    }
}
