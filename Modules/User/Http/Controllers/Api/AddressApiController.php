<?php

namespace Modules\User\Http\Controllers\Api;

use App\Helpers\Call;
use App\Helpers\ResponseJson;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Requests\AddressRequest;
use Modules\User\Services\AddressService;

abstract class AddressApiController extends Controller
{
    protected $addressService;
    public function __construct($addressService)
    {
        $this->addressService = $addressService;
    }
    abstract protected function typeCastingRequest(AddressRequest $request): null|AddressRequest;
    private function getTypeRequest(AddressRequest $request)
    {
        $request = $this->typeCastingRequest($request);
        if (!isset($request)) {
            throw new \Exception('Request is not valid');
        }
        return $request;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return Call::TryCatchResponseJson(function () {
            $user = Auth::user();
            // return $user;
            $deliveryAddresses = $this->addressService->getByUserId($user->id);
            return ResponseJson::success(data: $deliveryAddresses);
        });
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function baseStore(AddressRequest $req)
    {
        $request = $this->getTypeRequest($req);
        return Call::TryCatchResponseJson(function () use ($request) {
            $data = $request->all();
            $user = Auth::user();
            $deliveryAddress = $this->addressService->create($user->id, $data);
            return ResponseJson::success('Address created successfully',data: $deliveryAddress);
        });
    }

    public function setAddressDefault($id)
    {
        return Call::TryCatchResponseJson(function () use ($id) {
            $user = Auth::user();
            $success = $this->addressService->setAddressAsDefaultOfUser($id, true, $user->id);
            if ($success) {
                return ResponseJson::success('Address set as default successfully');
            }
            return ResponseJson::error('The address does not exist', 404);
        });
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function baseUpdate(AddressRequest $req, $id)
    {
        $request = $this->getTypeRequest($req);
        return Call::TryCatchResponseJson(function () use ($request, $id) {
            $data = $request->all();
            $user = Auth::user();
            $success = $this->addressService->update($user->id, $id, $data);
            if ($success) {
                return ResponseJson::success('Address update successfully');
            }
            return ResponseJson::error('The address does not exist', 404);
        });
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        return Call::TryCatchResponseJson(function () use ($id) {
            $user = Auth::user();
            $success = $this->addressService->delete($user->id, $id);
            if ($success) {
                return ResponseJson::success('Address deleted successfully');
            }
            return ResponseJson::error('The address does not exist', 404);
        });
    }
}
