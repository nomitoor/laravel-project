<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerPackages;
use Illuminate\Support\Facades\DB;

class CustomerPackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_all_customer_pack()
    {
        $data = DB::table('users')
            ->select('users.name','customer_packages.*')
            ->join('customer_packages','customer_packages.customer_id','=','users.id')
            ->get()
            ->toArray();
        return response()->json( array(
            'data' => $data
        ));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer_packages = CustomerPackages::all();
        return view('/customerPackage/index', $customer_packages );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $customer_packages)
    {
        $has_paid = '';
        if( $request->has_paid == '1' ){
            $has_paid = '0';
        }else{
            $has_paid = '1';
        }

        CustomerPackages::where( 'package_id', $customer_packages )->update(['has_paid' => $has_paid]);
        return response()->json(array('message' => 'Cutomer Package updated succesfully!', 'status' => 'success', 'code' => 201 ));
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
