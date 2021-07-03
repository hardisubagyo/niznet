@extends('layouts.home')

@section('content')
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
      </div>
      
      <div class="card-body">
        <form name="myform" action="{{ route('Laporan.excel') }}" method="post">
          @csrf
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Periode</label>
            <div class="input-group col-sm-4">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="far fa-calendar-alt"></i>
                </span>
              </div>
              <input type="text" class="form-control float-right" name="periode" id="periode">
            </div>
            @if(Auth::user()->id_level == 1)
            <div class="input-group col-sm-4">
              <select class="form-control" id="toko" name="toko">
                <option value="0">All</option>
                @foreach($toko as $items)
                  <option value="{{ $items->id }}">{{ $items->name }}</option>
                @endforeach
              </select>
            </div>
            @else
            @endif
            <div class="input-group col-sm-2">
              <button type="button" class="btn btn-sm" style="background-color: #ff6600; border-color: #ff6600;" id="btnPeriode"><i class="fas fa-search"></i></button>
              &nbsp;&nbsp;
                {{-- <button type="submit" class="btn btn-sm" style="background-color: #ff6600; border-color: #ff6600;"><i class="fas fa-file-excel"></i></button> --}}
            </div>
          </div>
        </form>
        <hr>

        <div>
          <div id="loading"></div>
          <table class="table table-striped">
            <thead>
              <tr>
                <th style="width: 5px">No</th>
                <th>Tgl</th>
                <th>Toko</th>
                <th>Pemesan</th>
                <th>Produk</th>
                <th>Merk</th>
                <th>Kategori</th>
                <th>Quantity</th>
                <th>Harga</th>
                <th>Total</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="ListTotalLaporanPeriode"></tbody>
            <thead>
              <tr>
                <td colspan="9"><b>TOTAL</b></td>
                <td id="TotalLaporanPeriode" style="text-align:right"></td>
              </tr>
            </thead>
          </table>
        </div>

      </div>
      
    </div>
    
  </div>


  <script>

   
    $(function () {

      $('#periode').daterangepicker({
        locale: {
          format: 'YYYY-M-D'
        }
      });

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $('#btnPeriode').click(function () {
        var fd;
        fd = new FormData();
        fd.append('periode', $('#periode').val());
        fd.append('toko', $('#toko').val());
        fd.append('_token', '{{ csrf_token() }}');
        $.ajax({
          data: fd,
          url: "{{ route('Laporan.periode') }}",
          type: "POST",
          dataType: 'json',
          processData: false,
          contentType: false,
          beforeSend: function () {
            $("#loading").html("");
            $("#loading").append("<button class='btn btn-primary'><i class='fas fa-spinner fa-spin'></i></button>");
          },
          success: function (data) {

            if(data.data.length == 0){
              $("#loading").html("");
              toastr.error('Data Kosong');
              return false;
            }else{
              var html_ltlp = '';
              $("#loading").html("");
              $("#ListTotalLaporanPeriode").html("");
              $("#TotalLaporanPeriode").html("");
              for(var pl = 0; pl <data.data.length; ++pl){
                var sts;
                var nops = pl + 1;
                if(data.data[pl]['status'] == '1'){
                  sts = "<span class='badge badge-warning'>Menunggu Pembayaran</span>";
                }else if(data.data[pl]['status'] == '2'){
                  sts = "<span class='badge badge-info'>Menunggu Verifikasi</span>";
                }else{
                  sts = "<span class='badge badge-success'>Transaksi Selesai</span>";
                }

                html_ltlp += '<tr>'+
                                '<td>'+nops+'</td>'+
                                '<td>'+data.data[pl]['created_at']+'</td>'+
                                '<td>'+data.data[pl]['namatoko']+'</td>'+
                                '<td>'+data.data[pl]['name']+'</td>'+
                                '<td>'+data.data[pl]['product_name']+'</td>'+
                                '<td>'+data.data[pl]['brand_name']+'</td>'+
                                '<td>'+data.data[pl]['category_name']+'</td>'+
                                '<td>'+data.data[pl]['quantity']+'</td>'+
                                '<td>'+data.data[pl]['price']+'</td>'+
                                '<td style="text-align:right">'+data.data[pl]['total']+'</td>'+
                                '<td style="text-align:right">'+sts+'</td>'+
                              '</tr>';
              }
              $("#ListTotalLaporanPeriode").append(html_ltlp);
              $("#TotalLaporanPeriode").html("<b>"+data.total.total+"</b>");
            }
          },
          error: function (data) {
            console.log(data);
            $("#loading").html("");
            toastr.error('Gagal Mencari Data')
          }
        });
      });

    });
  
  </script>


@endsection