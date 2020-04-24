@extends('layouts._layout')
@section('content')

@section('asset')
@endsection

<div class="demo-1">
    <h3></h3>
    <h3></h3>
  </div>

<div class="row" style="margin:1%;">
    <div class="col-sm-6">
      <div class="card-counter " style="background-color: #23afb5;">
        <i class="fa fa-shopping-cart"></i>
        <span class="count-numbers text-light" ></span>
        <span class="count-name text-light">Total Kios</span>
      </div>
    </div>
</div>
<div class="row" style="margin:1%;">
    <div class="col-sm-4">
      <div class="card-counter " style="height: 130px; background-color: #23afb5;">
        <i class="fa fa-money-bill-alt"></i>
        <span class="count-numbers text-light" ></span>
        <span class="count-name text-light text-right">Total Tagihan</span>
      </div>
    </div>

</div>
<div style="margin: 2%">
    <div class="row">
        <div class="card" style="width: 100%">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                      <div class="card-header">
                        <h4>Map Lokasi Kios</h4>
                      </div>
                        <div id="map" style="width: 100%; height: 375px;"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="card" style="width: 100%">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                      <div class="card-header">
                        <h4>Map Tracking Sales</h4>
                      </div>
                        <div id="map2" style="width: 100%; height: 375px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="margin: 2%">
      <div class="row">
        <div class="card" style="width: 100%">
          <div class="card-body">
            <div class="row">
            <div class="col-sm-6">
              <div class="card-header">
                <h4>Table Profit Penjualan <a href=""><button class="btn btn-info d-none d-lg-block m-l-15 mb-3 float-right"><i class="fas fa-link"></i> Detail</button></a></h4>
              </div>
                <table id="config-table" class="table display table-bordered table-striped no-wrap">
                    <thead>
                      <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Kios</th>
                        <th scope="col">Jumlah Profit</th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
                </div>

                <div class="col-sm-6">
                  <div class="card-header">
                    <h4>Grafik Profit Penjualan</h4>
                  </div>
                  <br>
                  <canvas id="kios_profit" height="182"></canvas>
                </div>
             </div>
          </div>
        </div>
      </div>
    </div>

    <div style="margin: 2%">
      <div class="row">
        <div class="card" style="width: 100%">
          <div class="card-body">
            <div class="row">
            <div class="col-sm-6">
              <div class="card-header">
                <h4>Table Urutan Pembelian Kios Terbesar <a href="{{ url('report') }}"><button class="btn btn-info d-none d-lg-block m-l-15 mb-3 float-right"><i class="fas fa-link"></i> Detail</button></a></h4>
              </div>
                <table id="config-table2" class="table display table-bordered table-striped no-wrap">
                    <thead>
                      <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Kios</th>
                        <th scope="col">Jumlah Pembelian</th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
            </div>

            <div class="col-sm-6">
              <div class="card-header">
                <h4>Grafik Urutan Pembelian Kios Terbesar</h4>
              </div>
                <br>
                  <canvas id="pembelian_kios" height="182"></canvas>
                </div>
               </div>
             </div>

        </div>
      </div>
    </div>

    <div style="margin: 2%">
      <div class="row">
        <div class="card" style="width: 100%">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="card-header">
                  <h4>Table Urutan penjualan sales <a href="{{ url('reportsales') }}"><button class="btn btn-info d-none d-lg-block m-l-15 mb-3 float-right"><i class="fas fa-link"></i> Detail</button></a></h4>
                </div>
                <table id="config-table4" class="table display table-bordered table-striped no-wrap">
                    <thead>
                      <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Sales</th>
                        <th scope="col">Jumlah Penjualan</th>
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
</div>
@endsection

@section('script')
<script src="{{URL::asset('js')}}/chart-ds.js"></script>
<script src="{{URL::asset('assets')}}/node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{URL::asset('assets')}}/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvTkPKa1jErT_Kh9ZPTIP2az48f8y0WGo&callback=initMap"></script>
<script src="https://www.gstatic.com/firebasejs/7.13.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.13.1/firebase-analytics.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.13.1/firebase-database.js"></script>
@endsection

