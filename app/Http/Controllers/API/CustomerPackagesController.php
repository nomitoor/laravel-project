<?php

namespace App\Http\Controllers\API;

use App\Models\CustomerPackages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Validator;

class CustomerPackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer_packages = CustomerPackages::all();
        return response()->json(array('data' => $customer_packages));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|integer|bail',
            'package_id' => 'required|integer|bail',
            'has_paid' => 'required|boolean|bail',
            'allowed_minutes' => 'required|string|bail',
            'country_code' => 'required|string|bail',
            'remaining_minutes' => 'required|string|bail'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        CustomerPackages::create($request->all());
        return response()->json(array('message' => 'New customer package created succesfully!', 'status' => 'success', 'code' => 201));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerPackages  $customerPackages
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $customer_packages)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|integer|bail',
            'package_id' => 'required|integer|bail',
            'has_paid' => 'required|boolean|bail',
            'allowed_minutes' => 'required|string|bail',
            'country_code' => 'required|string|bail',
            'remaining_minutes' => 'required|string|bail'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        CustomerPackages::where('customer_id', $customer_packages)->update($request->all());
        return response()->json(array('message' => 'Customer Package updated successfully!', 'status' => 'success', 'code' => 201));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerPackages  $customerPackages
     * @return \Illuminate\Http\Response
     */
    public function destroy($customer_packages)
    {
        CustomerPackages::where('customer_id', $customer_packages)->delete();
        return response()->json(array('message' => 'customer Package deleted succesfully!', 'status' => 'success', 'code' => 201));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerPackages  $customerPackages
     * @return \Illuminate\Http\Response
     */
    public function get_customer_details($customer_packages)
    {

        $customer_details = CustomerPackages::find($customer_packages);
        return response()->json(array('data' => $customer_details, 'message' => 'Customer Package updated successfully!', 'status' => 'success', 'code' => 201));
    }
}
