<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Packages;
use Illuminate\Http\Request;
use Validator;

class PackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Packages::all();
        return response()->json(array('data' => $packages));
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
            'price' => 'required|integer|bail',
            'stripe_package_id' => 'required|string|bail',
            'call_minutes' => 'required|string|bail',
            'call_country' => 'required|string|bail',
            'call_country_code' => 'required|string|bail',
            "allowed_calling_country" => 'required|string|bail',
            "excluded_calling_country" => 'required|string|bail'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        Packages::create([
            "package_name" => $request->package_name,
            "price" => $request->price,
            "package_type" => $request->package_type,
            "stripe_package_id" => $request->stripe_package_id,
            "call_minutes" => $request->call_minutes,
            "call_country" => $request->call_country,
            "call_country_code" => $request->call_country_code,
            "allowed_calling_country" => serialize( $request->allowed_calling_country ),
            "excluded_calling_country" => serialize( $request->excluded_calling_country ),
        ]);

        return response()->json(array('message' => 'New package created succesfully!', 'status' => 'success', 'code' => 201));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Packages  $packages
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Packages $package)
    {
        $validator = Validator::make($request->all(), [
            'price' => 'required|integer|bail',
            'stripe_package_id' => 'required|string|bail',
            'call_minutes' => 'required|string|bail',
            'call_country' => 'required|string|bail',
            'call_country_code' => 'required|string|bail'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Packages::where('package_id', $package->package_id)->update($request->all());
        return response()->json(array('message' => 'Package updated succesfully!', 'status' => 'success', 'code' => 201));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Packages  $packages
     * @return \Illuminate\Http\Response
     */
    public function destroy(Packages $package)
    {
        $package->delete();
        return response()->json(array('message' => 'Package deleted succesfully!', 'status' => 'success', 'code' => 201));
    }
}
