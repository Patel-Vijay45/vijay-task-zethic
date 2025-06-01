<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Services\AddressService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{


    public function __construct(private AddressService $addressService) {}


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        ResponseHelper::sendSuccess('Data Fetch Successfully', AddressResource::collection($this->addressService->getAllAddresss($request->all()))->response()->getData());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddressRequest $request)
    {
        ResponseHelper::sendSuccess('Data Fetch Successfully', AddressResource::make($this->addressService->createAddress($request->validated()))->response()->getData());
    } 

    /**
     * Update the specified resource in storage.
     */
    public function update(AddressRequest $request, Address $address)
    {
        $user = Auth::user();
        if ($user->cannot('update', $address)) {
            return ResponseHelper::sendSuccess('You are not authorized to view this address', code: Response::HTTP_FORBIDDEN);
        }
        ResponseHelper::sendSuccess('Data Fetch Successfully', AddressResource::make($this->addressService->updateAddress($address->id, $request->validated()))->response()->getData());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        $user = Auth::user();
        if ($user->cannot('delete', $address)) {
            return ResponseHelper::sendSuccess('You are not authorized to view this address', code: Response::HTTP_FORBIDDEN);
        }
        $this->addressService->deleteAddress($address->id);
        ResponseHelper::sendSuccess('Data Deleted Successfully');
    }
}
