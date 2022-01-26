@extends('layouts/contentLayoutMaster')

@section('title', 'Edit SIP Users')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')

<section id="multiple-column-form">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">

          <form class="form needs-validation create_packages" novalidate id="jquery-val-form">
            <div class="row">
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="username_column">Username</label>
                  <input
                    type="text"
                    id="username_column"
                    class="form-control"
                    placeholder="Username"
                    name="username_column"
                    required
                    value="{{ $sip_user->username }}"
                  />
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="password_column">Password</label>
                  <input
                    type="text"
                    id="password_column"
                    class="form-control"
                    placeholder="Password"
                    name="password_column"
                    required
                    value="{{ $sip_user->password }}"
                  />
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="host_name_column">Host Name</label>
                  <input
                    type="text"
                    id="host_name_column"
                    class="form-control"
                    placeholder="Host Name"
                    name="host_name_column"
                    required
                    value="{{ $sip_user->host_name }}"
                  />
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="port_column">Port</label>
                  <input
                    type="text"
                    id="port_column"
                    class="form-control"
                    placeholder="Port"
                    name="port_column"
                    required
                    value="{{ $sip_user->port }}"
                  />
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="country_code_column">Country Code</label>
                  <input
                    type="text"
                    id="country_code_column"
                    class="form-control"
                    placeholder="Country Code"
                    name="country_code_column"
                    required
                    value="{{ $sip_user->country_code }}"
                  />
                </div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary" id="update_sip_user" name="submit" value="Submit">Update</button>
                <button type="reset" class="btn btn-outline-secondary">Reset</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
@section('page-script')
    <script src="{{ asset(mix('js/scripts/forms/form-validation.js')) }}"></script>
    <script>
        $('#update_sip_user').click(function(){
          var token = $('meta[name="csrf-token"]').attr('content');
          var assetPath = $('body').attr('data-asset-path');

          var username_column = $("#username_column").val();
          var password_column = $('#password_column').val();
          var host_name_column = $("#host_name_column").val();
          var port_column = $("#port_column").val();
          var country_code_column = $("#country_code_column").val();
        
          var sip_id = "{{ $sip_user->id }}";

          $.ajax({
              url:  assetPath + 'sip-users/'+sip_id,
              type: 'PATCH',
              data:{
                  "_token": token,
                  "username":username_column,
                  "password":password_column,
                  "host_name":host_name_column,
                  "port":port_column,
                  "country_code":country_code_column,
              },
              success: function(result) {
                  $("#update_sip_user").attr("disabled", true);
                  window.location.href = assetPath+'sip-users/index';
              }
          });
        }); 
    </script>
@endsection
