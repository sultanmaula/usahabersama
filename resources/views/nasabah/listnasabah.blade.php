@extends('layouts._layout')

@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">List Nasabah</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Master</a></li>
                    <li class="breadcrumb-item active">Nasabah</li>
                </ol>
                <button type="button" onclick="location.href='{{ url('/nasabah/add') }}'"class="btn btn-info d-none d-lg-block m-l-15"><i
                        class="fa fa-plus-circle"></i> Tambah Nasabah</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive m-t-40">
                        <table id="nasabah-table" class="table display table-bordered table-striped no-wrap">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Kelompok</th>
                                    <th>No. Handphone</th>
                                    <th>NIK</th>
                                    <th>Foto</th>
                                    <th>Action</th>
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

{{-- modal delete monggo disesuaikan --}}
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="vcenter">Hapus Nasabah</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin menghapus data ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-ok">Delete</button>
            </div>
        </div>
    </div>
</div>
{{-- akhir modal delete --}}

@endsection

@section('script')
<!-- This is data table -->
<script src="{{URL::asset('assets')}}/node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{URL::asset('assets')}}/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js"></script>

<script>
    $(document).ready(function () {

       

        $('#confirm-delete').on('click', '.btn-ok', function(e) {
            var $modalDiv = $(e.delegateTarget);
            var id = $(this).data('recordId');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            //$.ajax({url: '/deletekios/' + id, type: 'POST'})
            $.post('/nasabah/delete/' + id).then()
            $modalDiv.addClass('loading');
            setTimeout(function() {
                $modalDiv.modal('hide').removeClass('loading');
                $('#nasabah-table').DataTable().ajax.reload();
            })
        });

        $('#confirm-delete').on('show.bs.modal', function(e) {
            var data = $(e.relatedTarget).data();
            $('.title', this).text(data.recordTitle);
            $('.btn-ok', this).data('recordId', data.recordId);
        });
        
        var today_table = $('#nasabah-table').DataTable({
            processing: true,
            serverSide: true,
            "ajax": "{{ route('list-nasabah-get') }}",
            columnDefs: [{
                targets: [0, 1, 2],
                className: 'mdl-data-table__cell--non-numeric'
            }],
            columns: [{
                    "data": null,
                    "sortable": false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'nama', name: 'nama' },
                { data: 'alamat', name: 'alamat' },
                { data: 'nama_kelompok', name: 'nama_kelompok' },
                { data: 'no_hp', name: 'no_hp' },
                { data: 'nik', name: 'nik' },
                { data: 'foto', name: 'foto',
                    render: function( data, type, full, meta ) {
                        return "<img src=\"/nasabah_image/" + data + "\" height=\"50\"/>";
                    }
                },
                { data: 'action', name: 'action' },
            ],
        });
      
        
    });
</script>
@endsection
