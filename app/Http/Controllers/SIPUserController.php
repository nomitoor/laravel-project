<?php

namespace App\Http\Controllers;

use App\Models\SIPUser;
use Illuminate\Http\Request;
use App\Imports\SIPUserImport;

class SIPUserController extends Controller
{
    public function add_sip_users( Request $request ){
        \Excel::import( new SIPUserImport , $request->csv_file );
        return view('/sipUsers/index');
    }
        
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_all_sip_users()
    {
        return response()->json( array(
            'data' => SIPUser::all()->toArray()
        ));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return view('/sipUsers/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view( '/sipUsers/create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        SIPUser::create([
            "username" => $request->username,
            "password" => $request->password,
            "host_name" => $request->host_name,
            "port" => $request->port,
            "country_code" => $request->country_code,
        ]);
        return response()->json(array('message' => 'New sip user created succesfully!', 'status' => 'success', 'code' => 201 ));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SIPUser  $sIPUser
     * @return \Illuminate\Http\Response
     */
    public function show(SIPUser $sIPUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SIPUser  $sIPUser
     * @return \Illuminate\Http\Response
     */
    public function edit(SIPUser $id)
    {
        return view( '/sipUsers/edit', [ "sip_user" => $id ] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SIPUser  $sIPUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SIPUser $sip_user)
    {
        unset( $request['_token'] );

        SIPUser::where( 'id', $sip_user->id )->update([
            "username" => $request->username,
            "password" => $request->password,
            "host_name" => $request->host_name,
            "port" => $request->port,
            "country_code" => $request->country_code,
        ]);
        return response()->json(array('message' => 'Sip user updated succesfully!', 'status' => 'success', 'code' => 201 ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SIPUser  $sIPUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(SIPUser $sip_user)
    {
        $sip_user->delete();
        return response()->json(array('message' => 'Package deleted succesfully!', 'status' => 'success', 'code' => 201 ));
    }
}
