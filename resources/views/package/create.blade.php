@extends('layouts/contentLayoutMaster')

@section('title', 'Create Packages')

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
                  <label for="package_name_column">Package Name</label>
                  <input type="text" id="package_name_column" class="form-control" placeholder="Package Name" name="package_name" required />
                </div>
              </div>
              <div class="col-md-3 col-12">
                <div class="form-group">
                  <label for="price_column">Price</label>
                  <input type="text" id="price_column" class="form-control" placeholder="Price" name="price" required />
                </div>
              </div>
              <div class="col-md-3 col-12">
                <div class="form-group">
                  <label for="package_type">Package Type</label>
                  <select id="package_type" class="form-control" name="package_type" required>
                    <option value="monthly">Monthly</option>
                    <option value="minutes">Minutes</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="stripe_package_id_column">Stripe Package Id</label>
                  <input type="text" id="stripe_package_id_column" class="form-control" placeholder="Stripe Package Id" name="stripe_package_id" required />
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="call_minutes_column">Call Minutes</label>
                  <input type="text" id="call_minutes_column" class="form-control" placeholder="Call Minutes" name="call_minutes" required />
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="call_country">Call Country</label>
                  <input type="text" id="call_country" class="form-control" name="call_country" placeholder="Call Country" required />
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="call_country_code_column">Call Country Code</label>
                  <input type="text" id="call_country_code_column" class="form-control" name="call_country_code" placeholder="Call Country Code" required />
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label>Allowed Country Codes</label>
                  <select class="select2 form-control" id="allowed_calling_country" name="allowed_calling_country" required multiple>
                    @foreach( $country_codes as $codes )
                    <option value="{{ '+'.$codes->phonecode.' '.$codes->name }}"> {{ '+'.$codes->phonecode.' '. $codes->name }} </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label>Excluded Calling Country</label>
                  <select class="select2 form-control" id="excluded_calling_country" name="excluded_calling_country" required multiple>
                    @foreach( $country_codes as $codes )
                    <option value="{{ '+'.$codes->phonecode.' '.$codes->name }}"> {{ '+'.$codes->phonecode.' '. $codes->name }} </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary" id="add_package" name="submit" value="Submit">Submit</button>
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
  $('#add_package').click(function() {
    var token = $('meta[name="csrf-token"]').attr('content');
    var assetPath = $('body').attr('data-asset-path');

    var package_name_column = $("#package_name_column").val();
    var package_type = $('#package_type').val();
    var price_column = $("#price_column").val();
    var stripe_package_id_column = $("#stripe_package_id_column").val();
    var call_minutes_column = $("#call_minutes_column").val();
    var call_country = $("#call_country").val();
    var call_country_code_column = $("#call_country_code_column").val();
    var allowed_calling_country = $("#allowed_calling_country").val();
    var excluded_calling_country = $("#excluded_calling_country").val();
    if (allowed_calling_country == '' || excluded_calling_country == '') {
      console.log('empty');
    } else {
      $.ajax({
        url: assetPath + 'packages',
        type: 'POST',
        data: {
          "_token": token,
          "package_name": package_name_column,
          "package_type": package_type,
          "price": price_column,
          "stripe_package_id": stripe_package_id_column,
          "call_minutes": call_minutes_column,
          "call_country": call_country,
          "call_country_code": call_country_code_column,
          "allowed_calling_country": allowed_calling_country,
          "excluded_calling_country": excluded_calling_country
        },
        success: function(result) {
          $("#add_package").attr("disabled", true);
          window.location.href = assetPath + 'packages';
        }
      });
    }

  });
</script>
@endsection