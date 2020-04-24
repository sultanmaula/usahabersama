@extends('layouts._layout')
@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">List Report</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Report</li>
                    <li class="breadcrumb-item active">List Report</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                   <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Pilih Sales</label>
                                <select name="id_kios" id="id_kios" class="form-control select2">
                                    <option value="">Pilih Sales</option>
                                    @foreach ($data as $item)
                                    <option value="{{$item->id}}">{{$item->nama_sales}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="control-label">Start Date</label>
                                    <div class="controls">
                                        <input type="date" name="start_date" id="start_date" class="form-control datepicker-autoclose" placeholder="Please select start date"> <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">End Date</label>
                                    <div class="controls">
                                        <input type="date" name="end_date" id="end_date" class="form-control datepicker-autoclose" placeholder="Please select end date"> <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="controls">
                                        <button style="margin-top: 30px;" type="text" id="btnFiterSubmitSearch" class="btn btn-info">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Pilih Tanggal</label>
                                    <select class="form-control select2" name="id" id="area">
                                        <option value=''>Pilih Tanggal</option>
                                    </select>
                                </div>
                            </div> -->
                </div>
                <div class="table-responsive m-t-40">
                    <table id="config-table" class="table display table-bordered table-striped no-wrap mdl-data-table dataTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Sales</th>
                                <th>Jumlah Penjualan</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<!-- This is data table -->
<script src="{{URL::asset('assets')}}/node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{URL::asset('assets')}}/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js"></script>
<script src="{{URL::asset('assets')}}/node_modules/datatablecetak/dataTables.buttons.min.js"></script>
<script src="{{URL::asset('assets')}}/node_modules/datatablecetak/buttons.flash.min.js"></script>
<script src="{{URL::asset('assets')}}/node_modules/datatablecetak/jszip.min.js"></script>
<script src="{{URL::asset('assets')}}/node_modules/datatablecetak/pdfmake.min.js"></script>
<script src="{{URL::asset('assets')}}/node_modules/datatablecetak/vfs_fonts.js"></script>
<script src="{{URL::asset('assets')}}/node_modules/datatablecetak/buttons.html5.min.js"></script>
<script src="{{URL::asset('assets')}}/node_modules/datatablecetak/buttons.print.min.js"></script>
<script src="{{URL::asset('assets')}}/node_modules/datatablecetak/moment.min.js"></script>
<script src="{{URL::asset('assets')}}/node_modules/datatablecetak/date.min.js"></script>

<script>

    $(document).ready(function() {
        $("#config-table").DataTable().destroy();
        var id_sales=$("#id_kios").val();

        var i=0;
        var table=$('#config-table').DataTable({
            dom: "<'row'<'col-sm-2'l><'col-sm-6'B><'col-sm-4'f>>" +
              "<'row'<'col-sm-12'tr>>" +
              "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [
                { extend: 'excel', className: 'btn btn-primary' },
                { extend: 'pdf', className: 'btn btn-primary' }
            ],
            processing: true,
            serverSide: true,
            ajax: {
              url: "{{ url('listreportsales') }}",
              type: 'GET',
              data: function (d) {
              d.start_date = $('#start_date').val();
              d.end_date = $('#end_date').val();
              console.log(d)
              }
            },
            columns: [
            {
                "data": null,"sortable": false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {data: 'nama_sales', name: 'nama_sales'},
            {data: 'total_tran', name: 'total_tran', render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' )},
            {data: 'detail', name: 'detail'},
            ],
        });
    });

    $('#btnFiterSubmitSearch').click(function(){
     $('#config-table').DataTable().draw(true);
  });


    $(document).ready(function() {
        $("#id_kios").change(function() {
            var provid = $("#id_kios").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if(provid){
                $.ajax({

                    type: 'GET',
                    url: '/listReportByNamaSales/' + provid,
                    dataType: 'json',
                    success: function(data) {
                        console.log(data)
                        $("#config-table").DataTable().destroy();
                        var table=$('#config-table').DataTable({
                            dom: "<'row'<'col-sm-2'l><'col-sm-6'B><'col-sm-4'f>>" +
                              "<'row'<'col-sm-12'tr>>" +
                              "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                            buttons: [
                                { extend: 'excel', className: 'btn btn-primary  mb-3' },
                                { extend: 'pdf', className: 'btn btn-primary  mb-3' }
                            ],
                            processing: true,
                            serverSide: true,
                            "ajax": "/listReportByNamaSales/"+provid,
                            columnDefs: [{
                                targets: [0, 1, 2],
                                className: 'mdl-data-table__cell--non-numeric'
                            }],
                            columns: [
                            {
                                "data": null,"sortable": false,
                                render: function (data, type, row, meta) {
                                    return meta.row + meta.settings._iDisplayStart + 1;
                                }
                            },
                            {data: 'nama_sales', name: 'nama_sales'},
                            {data: 'total_tran', name: 'total_tran', render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' )},
                            {data: 'detail', name: 'detail'},
                            ],
                        });
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
            else{
               $("#config-table").DataTable().destroy();
                var table=$('#config-table').DataTable({
                    dom: "<'row'<'col-sm-2'l><'col-sm-6'B><'col-sm-4'f>>" +
                      "<'row'<'col-sm-12'tr>>" +
                      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                    buttons: [
                        { extend: 'excel', className: 'btn btn-primary  mb-3' },
                        { extend: 'pdf', className: 'btn btn-primary  mb-3' }
                    ],
                    processing: true,
                    serverSide: true,
                    ajax: {
                      url: "{{ url('listreportsales') }}",
                      type: 'GET',
                      data: function (d) {
                      d.start_date = $('#start_date').val();
                      d.end_date = $('#end_date').val();
                      }
                    },
                    columns: [
                    {
                        "data": null,"sortable": false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {data: 'nama_sales', name: 'nama_sales'},
                    {data: 'total_tran', name: 'total_tran', render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' )},
                    {data: 'detail', name: 'detail'},
                    ],
                });

            }
        });
        //ajax untuk kota

    });

    // $(document).ready(function() {
    //     $("#id").change(function() {
    //         var provid = $("#id").val();
    //         // var id_kotaku = $("#area").val();
    //         $.ajaxSetup({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             }
    //         });
    //         if(provid){
    //             $.ajax({
    //                 type: 'GET',
    //                 url: '/listreport-bytgl/' + provid,
    //                 dataType: 'json',
    //                 success: function(data) {
    //                     console.log(data)
    //                     $("#config-table").DataTable().destroy();
    //                     var table=$('#config-table').DataTable({
    //                         dom: 'Bfrtip',
    //                         buttons: [
    //                         'excel', 'pdf'
    //                         ],
    //                         processing: true,
    //                         serverSide: true,
    //                         "ajax": "/listreport-bytgl/"+provid,
    //                         columnDefs: [{
    //                             targets: [0, 1, 2],
    //                             className: 'mdl-data-table__cell--non-numeric'
    //                         }],
    //                         columns: [
    //                         {
    //                             "data": null,"sortable": false,
    //                             render: function (data, type, row, meta) {
    //                                 return meta.row + meta.settings._iDisplayStart + 1;
    //                             }
    //                         },
    //                         {data: 'nama_Kios', name: 'nama_Kios'},
    //                         {data: 'nama_product', name: 'nama_product'},
    //                         {data: 'profit', name: 'profit'},
    //                         {data: 'nominal', name: 'nominal'},
    //                         {data: 'tanggal', name: 'tanggal'},
    //                         ],
    //                     });
    //                 },
    //                 error: function(data) {
    //                     console.log(data);
    //                 }
    //             });
    //         }
    //         else{
    //            $("#config-table").DataTable().destroy();
    //             var table=$('#config-table').DataTable({
    //                 dom: 'Bfrtip',
    //                 buttons: [
    //                 'excel', 'pdf'
    //                 ],
    //                 processing: true,
    //                 serverSide: true,
    //                 "ajax": "{{ route('listreport') }}",
    //                 columns: [
    //                 {
    //                     "data": null,"sortable": false,
    //                     render: function (data, type, row, meta) {
    //                         return meta.row + meta.settings._iDisplayStart + 1;
    //                     }
    //                 },
    //                 {data: 'nama_Kios', name: 'nama_Kios'},
    //                 {data: 'nama_product', name: 'nama_product'},
    //                 {data: 'profit', name: 'profit'},
    //                 {data: 'nominal', name: 'nominal'},
    //                 {data: 'tanggal', name: 'tanggal'},
    //                 ],
    //             });
    //         }
    //     });
    //     //ajax untuk kota

    // });
</script>
@endsection
