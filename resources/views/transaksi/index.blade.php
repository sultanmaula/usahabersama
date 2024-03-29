@extends('layouts._layout')
@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">List Transaksi</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <a href="{{route('add-transaksi')}}" type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Tambah Transaksi</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="config-table" class="table display table-bordered table-striped no-wrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Nasabah</th>
                                    <th>Nama Produk</th>
                                    <th>Status</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>Tanggal Jatuh Tempo</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksis as $t)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $t->nama }}</td>
                                    <td>{{ $t->nama_produk }}</td>
                                    <td>
                                        @if($t->status == 1)
                                            Lunas
                                        @else
                                            Belum Lunas
                                        @endif
                                    </td>
                                    <td>{{ $t->tanggal }}</td>
                                    <td>{{ $t->tanggal_jatuh_tempo }}</td>
                                    <td>
                                        <button class="btn btn-xs btn-danger" data-record-id="{{$t->id}}" data-record-title="The first one" data-toggle="modal" data-target="#confirm-delete"><span class="btn-label"><i class="fa fa-trash"></i></span></button>
                                        <a href='{{route("detail-transaksi", $t->id)}}' class="btn btn-xs btn-warning " type="button"><span class="btn-label"><i class="fa fa-eye"></i></span></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card">
                        {{ $transaksis->links() }}
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="vcenter">Hapus Transaksi</h4>
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
@endsection
@section('script')
<!-- This is data table -->
<script src="{{URL::asset('assets')}}/node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{URL::asset('assets')}}/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js"></script>
<script>
$('#confirm-delete').on('click', '.btn-ok', function(e) {
    var $modalDiv = $(e.delegateTarget);
    var id = $(this).data('recordId');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //$.ajax({url: '/deletekios/' + id, type: 'POST'})
    $.post('/transaksi/delete/' + id).then()
    $modalDiv.addClass('loading');
    setTimeout(function() {
        $modalDiv.modal('hide').removeClass('loading');
        location.reload();
    })
});
$('#confirm-delete').on('show.bs.modal', function(e) {
    var data = $(e.relatedTarget).data();
    $('.title', this).text(data.recordTitle);
    $('.btn-ok', this).data('recordId', data.recordId);
});

// $(document).ready(function() {
//     var i = 0;
//     var table = $('#config-table').DataTable({
//         processing: true,
//         serverSide: true,
//         ajax: "{{ route('list-transaksi-get') }}",
//         columns: [{
//                 data: null,
//                 sortable: false,
//                 render: function(data, type, row, meta) {
//                     return meta.row + meta.settings._iDisplayStart + 1;
//                 }
//             },
//             { data: 'nama_nasabah' },
//             { data: 'nama_produk' },
//             { data: 'status' },
//             { data: 'tanggal' },
//             { data: 'tanggal_jatuh_tempo' },
//             { data: 'action', name: 'action', orderable: false, searchable: false },
//         ],
//     });
// });
</script>
@endsection
