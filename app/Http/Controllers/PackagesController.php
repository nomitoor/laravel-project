<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Packages;
use Illuminate\Http\Request;
use Validator;
use App\Models\Country;

class PackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_all_package()
    {
        return response()->json(array(
            'data' => Packages::all()->toArray()
        ));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Packages::all();
        return view('/package/index', $packages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response_data = Country::all();
        return view('/package/create', ['country_codes' => $response_data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Packages::create([
            "package_name" => $request->package_name,
            "price" => $request->price,
            "package_type" => $request->package_type,
            "stripe_package_id" => $request->stripe_package_id,
            "call_minutes" => $request->call_minutes,
            "call_country" => $request->call_country,
            "call_country_code" => $request->call_country_code,
            "allowed_calling_country" => serialize($request->allowed_calling_country),
            "excluded_calling_country" => serialize($request->excluded_calling_country),
        ]);
        return response()->json(array('message' => 'New package created succesfully!', 'status' => 'success', 'code' => 201));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Packages $package)
    {
        $response_data = Country::all();
        return view('/package/edit', ["package" => $package, 'country_codes' => $response_data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Packages $package)
    {
        unset($request['_token']);

        Packages::where('package_id', $package->package_id)->update([
            "package_name" => $request->package_name,
            "price" => $request->price,
            "package_type" => $request->package_type,
            "stripe_package_id" => $request->stripe_package_id,
            "call_minutes" => $request->call_minutes,
            "call_country" => $request->call_country,
            "call_country_code" => $request->call_country_code,
            "allowed_calling_country" => serialize($request->allowed_calling_country),
            "excluded_calling_country" => serialize($request->excluded_calling_country),
        ]);
        return response()->json(array('message' => 'Package updated succesfully!', 'status' => 'success', 'code' => 201));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Packages $package)
    {
        $package->delete();
        return response()->json(array('message' => 'Package deleted succesfully!', 'status' => 'success', 'code' => 201));
    }
}
