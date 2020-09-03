@extends('layouts._layout')
@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">List Angsuran</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <a href="{{route('add-angsuran')}}" type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Tambah Angsuran</a>
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
                                    <th>Transaksi</th>
                                    <th>Cicilan Ke</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0;?>
                                @foreach ($transaksi as $t)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $t->nama_produk }}</td>
                                    <td>{{ $angsurans[$i][0]->cicilan_ke }}</td>
                                    <td>{{ $t->tanggal }}</td>
                                    <td>
                                        @if ($t->status == 1)
                                            Lunas
                                        @else
                                            Belum Lunas
                                        @endif
                                    </td>
                                    <td><a href='{{route("detail-angsuran", $angsurans[$i][0]->id)}}' class="btn btn-xs btn-warning " type="button"><span class="btn-label"><i class="fa fa-eye"></i></span></a></td>
                                </tr>
                                <?php $i++;?>
                                @endforeach
                            </tbody>
                        </table>
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
    $.post('/angsuran/delete/' + id).then()
    $modalDiv.addClass('loading');
    setTimeout(function() {
        $modalDiv.modal('hide').removeClass('loading');
        $('#config-table').DataTable().ajax.reload();
    })
});
$('#confirm-delete').on('show.bs.modal', function(e) {
    var data = $(e.relatedTarget).data();
    $('.title', this).text(data.recordTitle);
    $('.btn-ok', this).data('recordId', data.recordId);
});

</script>
@endsection
