@extends('layouts.home')

@section('content')
  <div class="col-md-12">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="background-color: #ff6600;">
                    <h3 class="card-title"><b>Penjualan Berdasarkan Kategori</b></h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="barChartKategori"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="background-color: #ff6600;">
                    <h3 class="card-title"><b>Penjualan Berdasarkan Merk</b></h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="barChartMerk"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="background-color: #ff6600;">
                    <h3 class="card-title"><b>Total Pengantar Kurir</b></h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="TblKurir" class="table table-sm table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>  
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <div class="modal fade" id="HistoryKurir">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Pengantaran Pesanan </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">No</th>
                                <th>Nama Kurir</th>
                                <th>Kode Checkout</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="TblPengantarPesanan"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="DetailHistoryKurir">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Detail Pengantaran Pesanan </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">No</th>
                                <th>Nama Produk</th>
                                <th>Nama Merk</th>
                                <th>Nama Kategori</th>
                                <th>Harga</th>
                                <th>Kuantitas</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="TblDetailPengantarPesanan"></tbody>
                        <thead id="TotalDetailPengantarPesanan"></thead>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}

  </div>


  <script>

    $(function() {
        "use strict";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#TblKurir').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('Dashboard.kurir') }}",
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'name'},
                {data: 'total'},
                {data: 'action', className: 'uniqueClassName'}
            ]
        });

        $.get("{{ route('Dashboard.PenjualanPerKategori') }}", function (data) {

            var namakategori = [];
            var jumlahkategori = [];

            for(var a = 0; a < data.length; ++a){
              namakategori.push(data[a]['category_name']);
              jumlahkategori.push(data[a]['total']);
            }

            var options = {
                chart: {
                    height: 250,
                    type: 'bar',
                    foreColor: '#4e4e4e',
                    toolbar: {
                          show: true
                        }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'  
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                grid:{
                    show: true,
                    borderColor: 'rgba(255, 255, 255, 0.00)',
                },
                series: [{
                    name: 'Jumlah Penjualan',
                    data: jumlahkategori
                }],
                xaxis: {
                    categories: namakategori,
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        gradientToColors: [ '#ffc107', '#08a50e', '#7f00ff'],
                        shadeIntensity: 1,
                        type: 'horizontal',
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100, 100, 100]
                    },
                },
                colors: ["#ff8000"],
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function (val) {
                            return " " + val + " "
                        }
                    }
                }
            }

            var chart = new ApexCharts(
                document.querySelector("#barChartKategori"),
                options
            );

            chart.render();
        });

        $.get("{{ route('Dashboard.PenjualanPerMerk') }}", function (data) {
            var namaMerk = [];
            var jumlahMerk = [];

            for(var a = 0; a < data.length; ++a){
              namaMerk.push(data[a]['brand_name']);
              jumlahMerk.push(data[a]['total']);
            }

            var optionsMerk = {
                chart: {
                    height: 250,
                    type: 'bar',
                    foreColor: '#4e4e4e',
                    toolbar: {
                          show: true
                        }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        columnWidth: '30%',
                        endingShape: 'rounded'  
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                grid:{
                    show: true,
                    borderColor: 'rgba(255, 255, 255, 0.00)',
                },
                series: [{
                    name: 'Jumlah Penjualan',
                    data: jumlahMerk
                }],
                xaxis: {
                    categories: namaMerk,
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        gradientToColors: [ '#ffd454', '#08a50e', '#7f00ff'],
                        shadeIntensity: 1,
                        type: 'horizontal',
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100, 100, 100]
                    },
                },
                colors: ["#ff8000"],
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function (val) {
                            return " " + val + ""
                        }
                    }
                }
            }

            var chartMerk = new ApexCharts(
                document.querySelector("#barChartMerk"),
                optionsMerk
            );

            chartMerk.render();
        });

        $('body').on('click', '.View', function () {
            var id = $(this).data('id');
            $('#TblPengantarPesanan').html('');
            var html_pengantar_pesanan = '';
            var stat = '';
            $.get("{{ url('dashboard/detailKurir') }}" + "/" + id, function(data){
                $('#HistoryKurir').modal({backdrop: 'static', keyboard: false});    
                for(var a=0; a<data.length; ++a){
                    var no = a + 1;
                    if(data[a]['status'] == '3'){
                        stat = "Sukses";
                    }else if(data[a]['status'] == '2'){
                        stat = "Pesanan Sedang Dalam Pengiriman";
                    }else if(data[a]['status'] == '1'){
                        stat = "Kurir Belum Menerima Pesanan Yang Sudah Diberikan";
                    }

                    html_pengantar_pesanan += '<tr>'+
                                                '<td>'+no+'</td>'+
                                                '<td>'+data[a]['name']+'</td>'+
                                                '<td><a href="javascript:void(0);" onClick="detailCheckout('+data[a]['checkout_code']+')">'+data[a]['checkout_code']+'</a></td>'+
                                                '<td>'+data[a]['created_at']+'</td>'+
                                                '<td>'+stat+'</td>'+
                                                '<td>'+data[a]['total_price']+'</td>'+
                                                '</tr>';
                }

                $('#TblPengantarPesanan').append(html_pengantar_pesanan);
            });
        });

    });

    function detailCheckout(code){
        $('#TblDetailPengantarPesanan').html('');
        $('#TotalDetailPengantarPesanan').html('');
        var html_detail_pengantar_pesanan = '';
        $.get("{{ url('dashboard/DetailKurirPesanan') }}" + "/" + code, function(data){
            console.log(data.total[0]['total']);
            $('#DetailHistoryKurir').modal({backdrop: 'static', keyboard: false});
            for(var as=0; as<data.list.length; ++as){
                var nos = as + 1;
                html_detail_pengantar_pesanan += '<tr>'+
                                                    '<td>'+nos+'</td>'+
                                                    '<td>'+data.list[as]['product_name']+'</td>'+
                                                    '<td>'+data.list[as]['brand_name']+'</a></td>'+
                                                    '<td>'+data.list[as]['category_name']+'</td>'+
                                                    '<td>'+data.list[as]['price']+'</td>'+
                                                    '<td>'+data.list[as]['quantity']+'</td>'+
                                                    '<td>'+data.list[as]['total']+'</td>'+
                                                '</tr>';
            }

            $('#TblDetailPengantarPesanan').append(html_detail_pengantar_pesanan);
            $('#TotalDetailPengantarPesanan').html('<tr><td colspan="6"><b>TOTAL</b></td><td><b>'+data.total[0]['total']+'</b></td></tr>');
        });
    }

  </script>


@endsection