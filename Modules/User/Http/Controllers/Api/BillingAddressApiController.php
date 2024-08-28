<?php

namespace Modules\User\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Modules\User\Http\Requests\AddressRequest;
use Modules\User\Http\Requests\BillingAddressRequest;
use Modules\User\Services\BillingAddressService;

class BillingAddressApiController extends AddressApiController
{

    // protected $billingAddressService;
    public function __construct(BillingAddressService $billingAddressService)
    {
        // $this->billingAddressService = $billingAddressService;
        parent::__construct($billingAddressService);
    }
    protected function typeCastingRequest(AddressRequest $request): null|BillingAddressRequest
    {

        if ($request instanceof BillingAddressRequest) {
            /** @var BillingAddressRequest $billingAddressRequest */
            $billingAddressRequest = $request;
            return $billingAddressRequest;
        }
        return null;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(BillingAddressRequest $req)
    {
        return $this->baseStore($req);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(BillingAddressRequest $req, $id)
    {
        return $this->baseUpdate($req, $id);
    }
}
