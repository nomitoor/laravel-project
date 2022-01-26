/**
 * DataTables Advanced
 */

'use strict';

// Advanced Search Functions Starts
// --------------------------------------------------------------------

// Filter column wise function
function filterColumn(i, val) {
  if (i == 5) {
    var startDate = $('.start_date').val(),
      endDate = $('.end_date').val();
    filterByDate(i, startDate, endDate); // We call our filter function

    $('.dt-advanced-search').dataTable().fnDraw();
  } else {
    $('.dt-advanced-search').DataTable().column(i).search(val, false, true).draw();
  }
}

// Datepicker for advanced filter
var separator = ' - ',
  rangePickr = $('.flatpickr-range'),
  dateFormat = 'MM/DD/YYYY';
var options = {
  autoUpdateInput: false,
  autoApply: true,
  locale: {
    format: dateFormat,
    separator: separator
  },
  opens: $('html').attr('data-textdirection') === 'rtl' ? 'left' : 'right'
};

//
if (rangePickr.length) {
  rangePickr.flatpickr({
    mode: 'range',
    dateFormat: 'm/d/Y',
    onClose: function (selectedDates, dateStr, instance) {
      var startDate = '',
        endDate = new Date();
      if (selectedDates[0] != undefined) {
        startDate =
          selectedDates[0].getMonth() + 1 + '/' + selectedDates[0].getDate() + '/' + selectedDates[0].getFullYear();
        $('.start_date').val(startDate);
      }
      if (selectedDates[1] != undefined) {
        endDate =
          selectedDates[1].getMonth() + 1 + '/' + selectedDates[1].getDate() + '/' + selectedDates[1].getFullYear();
        $('.end_date').val(endDate);
      }
      $(rangePickr).trigger('change').trigger('keyup');
    }
  });
}

// Advance filter function
// We pass the column location, the start date, and the end date
var filterByDate = function (column, startDate, endDate) {
  // Custom filter syntax requires pushing the new filter to the global filter array
  $.fn.dataTableExt.afnFiltering.push(function (oSettings, aData, iDataIndex) {
    var rowDate = normalizeDate(aData[column]),
      start = normalizeDate(startDate),
      end = normalizeDate(endDate);

    // If our date from the row is between the start and end
    if (start <= rowDate && rowDate <= end) {
      return true;
    } else if (rowDate >= start && end === '' && start !== '') {
      return true;
    } else if (rowDate <= end && start === '' && end !== '') {
      return true;
    } else {
      return false;
    }
  });
};

// converts date strings to a Date object, then normalized into a YYYYMMMDD format (ex: 20131220). Makes comparing dates easier. ex: 20131220 > 20121220
var normalizeDate = function (dateString) {
  var date = new Date(dateString);
  var normalized =
    date.getFullYear() + '' + ('0' + (date.getMonth() + 1)).slice(-2) + '' + ('0' + date.getDate()).slice(-2);
  return normalized;
};
// Advanced Search Functions Ends

$(function () {
  // CSRF token
  var token = $('meta[name="csrf-token"]').attr('content');
  var isRtl = $('html').attr('data-textdirection') === 'rtl';
  
  // Packages Start
  var packages_table = $('.packages-table'),
    assetPath = '../../../app-assets/';

  if ($('body').attr('data-framework') === 'laravel') {
    assetPath = $('body').attr('data-asset-path');
  }

  if (packages_table.length) {
    var dt_ajax = packages_table.dataTable({
      processing: true,
      dom:'<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',

      ajax: assetPath + 'packages/all',
      columns: [ 
        { data: 'package_id' },
        { data: 'package_name' },
        { data: 'package_type' },
        { data: 'price' },
        { data: 'stripe_package_id' },
        { data: 'call_minutes' },
        { data: 'call_country' },
        { data: 'call_country_code' },
      ],
      columnDefs: [
        {
          "targets": [ 0 ],
          "visible": false,
          "searchable": false
        },
        {
          "targets": 2,
          render: function (data, type, full, meta) {
            return data.charAt(0).toUpperCase()+ data.slice(1)
          }
        },
        {
          className: 'control',
          orderable: false,
          targets: 0
        },
        {
          targets: -1,
          title: 'Actions',
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-inline-flex">' +
              '<a class="pr-1 dropdown-toggle hide-arrow text-primary" data-toggle="dropdown">' +
              feather.icons['menu'].toSvg({ class: 'font-large-6' }) +
              '</a>' +
              '<div class="dropdown-menu view-record">' +
              '<a href="javascript:;" class="dropdown-item edit-record">' +
              feather.icons['edit'].toSvg({ class: 'mr-50 font-small-4' }) +
              'Edit</a>' +
              '<a href="javascript:;" class="dropdown-item delete-record">' +
              feather.icons['trash-2'].toSvg({ class: 'mr-50 font-small-4' }) +
              'Delete</a>' +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      language: {
        paginate: {
          previous: '&nbsp;',
          next: '&nbsp;'
        }
      }
    });
  }  

  $('.packages-table tbody').on('click', '.delete-record', function(){
    var row = $(this).closest('tr');
    var data = $('.packages-table').dataTable().fnGetData(row);

    $.ajax({
      url:  assetPath + 'packages/'+data.package_id,
      type: 'DELETE',
      data:{
        "_token": token,
      },
      success: function(result) {
        row.fadeOut();
      }
    });
  });

  $('.packages-table tbody').on('click', '.edit-record', function(){
    var row = $(this).closest('tr');
    var data = $('.packages-table').dataTable().fnGetData(row);
    window.location.href = assetPath + 'packages/'+data.package_id;
  });
  // Packages end


  // Customer Packages Start
  var customer_packages_table = $('.customer-packages-table'),
    assetPath = '../../../app-assets/';

  if ($('body').attr('data-framework') === 'laravel') {
    assetPath = $('body').attr('data-asset-path');
  }

  if (customer_packages_table.length) {
    var dt_ajax = customer_packages_table.dataTable({
      processing: true,
      dom:'<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',

      ajax: assetPath + 'customer-packages/all',
      columns: [ 
        { data: 'name' },
        { data: 'package_id' },
        { data: 'has_paid' },
        { data: 'allowed_minutes' },
        { data: 'country_code' },
        { data: 'remaining_minutes' },
      ],
      "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
        if ( aData.has_paid == "0" ){
          $('td', nRow).css({'background-color':'#f08182', 'color':'white', 'font-weight':'bold'});
        }
      },
      columnDefs: [
      //   {
      //     className: 'control',
      //     orderable: false,
      //     targets: 0
      //   },
        {
          targets: -1,
          title: 'Actions',
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-inline-flex">' +
              '<button class="btn btn-primary" id="update_payment_status">Payment status</button>' +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      language: {
        paginate: {
          previous: '&nbsp;',
          next: '&nbsp;'
        }
      }
    });
  }  
  $('.customer-packages-table tbody').on('click', '#update_payment_status', function(){
      var row = $(this).closest('tr');
      var data = $('.customer-packages-table').dataTable().fnGetData(row);
      var customer_id = data.customer_id;
      var package_id = data.package_id;
      var has_paid = data.has_paid;
      
      $.ajax({
        url:  assetPath + 'customer-packages/'+package_id,
        type: 'PATCH',
        data:{
          "_token": token,
          "customer_id":customer_id,
          "has_paid":has_paid
        },
        success: function(result) {
          window.location.reload();
        }
      });

    })
    // $('.customer-packages-table tbody').on('click', '.delete-record', function(){
    // var row = $(this).closest('tr');
    // var data = $('.customer-packages-table').dataTable().fnGetData(row);

    // $.ajax({
      // url:  assetPath + 'packages/'+data.package_id,
      // type: 'DELETE',
      // data:{
        // "_token": token,
      // },
      // success: function(result) {
        // row.fadeOut();
      // }
    // });
  // });

  // $('.customer-packages-table tbody').on('click', '.edit-record', function(){
  //   var row = $(this).closest('tr');
  //   var data = $('.customer-packages-table').dataTable().fnGetData(row);
  //   window.location.href = assetPath + 'packages/'+data.package_id;
  // });

  //Customer Packages end

  // User Start
  var users = $('.user-table'),
    assetPath = '../../../app-assets/';

  if ($('body').attr('data-framework') === 'laravel') {
    assetPath = $('body').attr('data-asset-path');
  }

  if (users.length) {
    var dt_ajax = users.dataTable({
      processing: true,
      dom:'<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',

      ajax: assetPath + 'users/all',
      columns: [ 
        { data: 'name' },
        { data: 'email' },
        { data: 'phone' }
      ],
      // columnDefs: [
      //   {
      //     className: 'control',
      //     orderable: false,
      //     targets: 0
      //   },
      //   {
      //     targets: -1,
      //     title: 'Actions',
      //     orderable: false,
      //     render: function (data, type, full, meta) {
      //       return (
      //         '<div class="d-inline-flex">' +
      //         '<a class="pr-1 dropdown-toggle hide-arrow text-primary" data-toggle="dropdown">' +
      //         feather.icons['menu'].toSvg({ class: 'font-large-6' }) +
      //         '</a>' +
      //         '<div class="dropdown-menu view-record">' +
      //         '<a href="javascript:;" class="dropdown-item edit-record">' +
      //         feather.icons['edit'].toSvg({ class: 'mr-50 font-small-4' }) +
      //         'Edit</a>' +
      //         '<a href="javascript:;" class="dropdown-item delete-record">' +
      //         feather.icons['trash-2'].toSvg({ class: 'mr-50 font-small-4' }) +
      //         'Delete</a>' +
      //         '</div>' +
      //         '</div>'
      //       );
      //     }
      //   }
      // ],
      language: {
        paginate: {
          previous: '&nbsp;',
          next: '&nbsp;'
        }
      }
    });
  }  

  $('.user-table tbody').on('click', '.delete-record', function(){
    var row = $(this).closest('tr');
    var data = $('.user-table').dataTable().fnGetData(row);

    $.ajax({
      url:  assetPath + 'packages/'+data.package_id,
      type: 'DELETE',
      data:{
        "_token": token,
      },
      success: function(result) {
        row.fadeOut();
      }
    });
  });

  $('.user-table tbody').on('click', '.edit-record', function(){
    var row = $(this).closest('tr');
    var data = $('.user-table').dataTable().fnGetData(row);
    window.location.href = assetPath + 'packages/'+data.package_id;
  });
  // User end

  // SIP User Start
  var sip_user = $('.sip-user-table'),
    assetPath = '../../../app-assets/';

  if ($('body').attr('data-framework') === 'laravel') {
    assetPath = $('body').attr('data-asset-path');
  }

  if (sip_user.length) {
    var dt_ajax = sip_user.dataTable({
      processing: true,
      dom:'<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',

      ajax: assetPath + 'sip-users/all',
      columns: [
        { data: 'id' },
        { data: 'username' },
        { data: 'password' },
        { data: 'host_name' },
        { data: 'port' },
        { data: 'country_code' },
      ],
      columnDefs: [
        {
          "targets": [ 0 ],
          "visible": false,
          "searchable": false
        },
        {
          className: 'control',
          orderable: false,
          targets: 0
        },
        {
          targets: -1,
          title: 'Actions',
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-inline-flex">' +
              '<a class="pr-1 dropdown-toggle hide-arrow text-primary" data-toggle="dropdown">' +
              feather.icons['menu'].toSvg({ class: 'font-large-6' }) +
              '</a>' +
              '<div class="dropdown-menu view-record">' +
              '<a href="javascript:;" class="dropdown-item edit-sip-user">' +
              feather.icons['edit'].toSvg({ class: 'mr-50 font-small-4' }) +
              'Edit</a>' +
              '<a href="javascript:;" class="dropdown-item delete-sip-user">' +
              feather.icons['trash-2'].toSvg({ class: 'mr-50 font-small-4' }) +
              'Delete</a>' +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      language: {
        paginate: {
          previous: '&nbsp;',
          next: '&nbsp;'
        }
      }
    });
  }  

  $('.sip-user-table tbody').on('click', '.delete-sip-user', function(){
    var row = $(this).closest('tr');
    var data = $('.sip-user-table').dataTable().fnGetData(row);

    $.ajax({
      url:  assetPath + 'sip-users/'+data.id,
      type: 'DELETE',
      data:{
        "_token": token,
      },
      success: function(result) {
        row.fadeOut();
      }
    });
  });

  $('.sip-user-table tbody').on('click', '.edit-sip-user', function(){
    var row = $(this).closest('tr');
    var data = $('.sip-user-table').dataTable().fnGetData(row);
    window.location.href = assetPath + 'sip-users/'+data.id;
  });
  // SIP User end


  // Filter form control to default size for all tables
  $('.dataTables_filter .form-control').removeClass('form-control-sm');
  $('.dataTables_length .custom-select').removeClass('custom-select-sm').removeClass('form-control-sm');
});
