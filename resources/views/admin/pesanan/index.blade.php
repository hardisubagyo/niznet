@extends('layouts.home')

@section('content')
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
      </div>
      
      <div class="card-body">
        <div class="row">
          <button type="button" class="btn btn-primary btn-sm" id="perbaruiPesanan"><i class="fas fa-sync"></i> Perbarui Data Pesanan</button>
        </div>
        <br>
        <div class="table-responsive">
          <table id="TblPesanan" class="table table-sm table-bordered table-striped" width="100%">
            <thead>
              <tr>
                <th>No</th>
                <th>Toko</th>
                <th>Pemesan</th>
                <th>Kode Checkout</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Payment</th>
                <th>No Ref</th>
                <th>Bank</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>  
        </div>
        
        <div class="modal fade" id="FormPesanan">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="titleform"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">

                <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Produk</th>
                      <th>Kategori</th>
                      <th>Merk</th>
                      <th>Jumlah</th>
                      <th>Harga</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody id="ListPesanan"></tbody>
                  <tfoot>
                    <tr>
                      <th colspan="6"><b>JUMLAH</b></th>
                      <th id="TotalPesanan" style="text-align:right"></th>
                    </tr>
                  </tfoot>
                </table>
              </div>
              
              </div>
              <div class="modal-footer right-content-between" id="savebutton">
               
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

        <div class="modal fade" id="BuktiPembayaran">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Bukti Pembayaran</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">

                <div id="ImgBuktiPembayaran"></div>
                  
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

      </div>
      
    </div>
    
  </div>


  <script>

   
    $(function () {

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      var table = $('#TblPesanan').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('Pesanan.scopedata') }}",
        columns: [
            {data: 'DT_RowIndex'},
            {data: 'toko'},
            {data: 'pemesan'},
            {data: 'checkout_code'},
            {data: 'created_at'},
            {
              data: 'total_price',
              render: $.fn.dataTable.render.number( '.', '.', 0, 'Rp ' )
            },
            {data: 'name_payment'},
            {data: 'no_reference'},
            {data: 'nama_bank'},
            {
              data: 'status',
              render: function (data, type, row) {
                  if (data == 1) {
                    return "<span class='badge badge-warning'>Menunggu Pembayaran</span>";
                  }else if(data == 2){
                    return "<span class='badge badge-info'>Menunggu Verifikasi</span>";
                  }else{
                    return "<span class='badge badge-success'>Transaksi Selesai</span>";
                  }
              }
            },
            {data: 'action', className: 'uniqueClassName'}
        ]
      });

      $('body').on('click', '.View', function () {
        var id = $(this).data('id');
        $.get("{{ url('pesanan/edit') }}" +'/' + id, function (data) {
          console.log(data)
          $('#titleform').html("");
          $('#titleform').html("Data Pesanan");
          $('#FormPesanan').modal({backdrop: 'static', keyboard: false});
          $('#ListPesanan').html('');
          $('#TotalPesanan').html('');
          $('#savebutton').html('');
          var html = '';
          for(var x=0; x<data.list.length; ++x){
            var no = x + 1;

            var category_name = '';
            if(data.list[x].category_name == null){
              category_name = '';
            }else{
              category_name = data.list[x].category_name;
            }

            var brand_name = '';
            if(data.list[x].brand_name == null){
              brand_name = '';
            }else{
              brand_name = data.list[x].brand_name;
            }

            html += '<tr><td>'+ no +'</td><td>'+data.list[x].product_name+'</td><td>'+category_name+'</td><td>'+brand_name+'</td><td>'+data.list[x].quantity+'</td><td style="text-align:right">'+ numeral(data.list[x].price).format('0,0')+'</td><td style="text-align:right">'+numeral(data.list[x].total).format('0,0')+'</td></tr>';
          }

          $('#ListPesanan').append(html);
          $('#TotalPesanan').html('<b>'+numeral(data.total[0].total).format('0,0')+'</b>');

          if(data.status.status == 2){
            $('#savebutton').html('');
            var html_verif = '';
            if(data.status.id_payment_method == '2'){
              html_verif += '<div class="form-group row col-12">\
                              <input type="hidden" value="'+data.status.id_payment_method+'" id="id_payment_method">\
                              <div class="col-sm-4">\
                                <input type="text" class="form-control" placeholder="No Reference" id="no_reference">\
                              </div>\
                              <div class="col-sm-3">\
                                <select class="form-control" id="kurirpengantar">\
                                  <option value="2">Transaksi Selesai</option>\
                                </select>\
                              </div>\
                              <div class="col-sm-5">\
                                  <button type="button" class="btn btn-success btn-sm viewstruk" data-idcheckout="'+ data.status.checkout_code +'"><i class="fas fa-eye"></i> Lihat Pembayaran </button>\
                                  <button type="button" class="btn bg-gradient-primary btn-sm" id="teruskanPesanan" data-checkout_code='+data.status.checkout_code+'>Update Status</button>\
                                </div>\
                              </div>';  
            }else{
              html_verif += '<div class="form-group row col-12">\
                              <input type="hidden" value="'+data.status.id_payment_method+'" id="id_payment_method">\
                              <div class="col-sm-3">\
                                <select class="form-control" id="kurirpengantar">\
                                  <option value="2">Transaksi Selesai</option>\
                                </select>\
                              </div>\
                              <div class="col-sm-7">\
                                  <button type="button" class="btn btn-success btn-sm viewstruk" data-idcheckout="'+ data.status.checkout_code +'"><i class="fas fa-eye"></i> Lihat Pembayaran </button>\
                                  <button type="button" class="btn bg-gradient-primary btn-sm" id="teruskanPesanan" data-checkout_code='+data.status.checkout_code+'>Update Status</button>\
                                </div>\
                              </div>';  
            }
            
            $('#savebutton').append(html_verif);

          }else if(data.status.status == 3){
            $('#savebutton').html('');
            $('#savebutton').html('<div class="row col-12"><button type="button" class="btn btn-success btn-sm viewstruk" data-idcheckout="'+ data.status.checkout_code +'"><i class="fas fa-clipboard-check"></i> Transaksi Selesai <i class="fas fa-check-circle"></i></button></div>')
          }

        })
      });

      /*$('body').on('click', '#verifikasipesanan', function () {
        var checkout_code = $(this).data('checkout_code');

        Swal.fire({
            text: "Verifikasi Pesanan ?",
            type: 'warning',
            buttons: true,
            dangerMode: true,
            showCancelButton: true,
        })
        .then((willDelete) => {
            if (willDelete.value == true) {

              var fd;
              fd = new FormData();
              fd.append('checkout_code', checkout_code);
              fd.append('_token', '{{ csrf_token() }}');
              $.ajax({
                data: fd,
                url: "{{ route('Pesanan.verifikasiPesanan') }}",
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function () {
                  $("#savebutton").html("");
                  $("#savebutton").append("<button class='btn btn-primary'><i class='fas fa-spinner fa-spin'></i></button>");
                },
                success: function (data) {
                  table.draw();
                  console.log(data);
                  $('#savebutton').html("");
                  $.get("{{ url('pesanan/pilihKurir') }}", function (datas) {
                    console.log(datas);
                    var html_kurir = '';
                    html_kurir += '<div class="form-group row col-12">\
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">Pilih Kurir</label>\
                                    <div class="col-sm-6">\
                                      <select class="form-control" id="kurirpengantar">';
                    for(var y=0; y<datas.length; ++y){
                      html_kurir += '<option value="'+datas[y].id+'">'+datas[y].name+'</option>'
                    }
                        html_kurir += '</select>\
                                      </div>\
                                      <div class="col-sm-4">\
                                        <button type="button" class="btn bg-gradient-primary btn-sm" id="teruskanPesanan" data-checkout_code='+data.checkout_code+'>Kirim Pesanan</button>\
                                      </div>\
                                    </div>';
                    $('#savebutton').append(html_kurir);
                  });
                  toastr.success('Verifikasi Pesanan Berhasil')
                },
                error: function (data) {
                  $('#savebutton').html('<button type="button" class="btn bg-gradient-primary btn-sm" id="verifikasipesanan" data-checkout_code='+checkout_code+'>Verifikasi Pemesanan</button>')
                  toastr.error('Verifikasi Pesanan Gagal')
                }
              });
            }
        });
      });*/

      $('body').on('click', '#teruskanPesanan', function () {
        var checkout_code = $(this).data('checkout_code');
        var id_payment_method = $('#id_payment_method').val();
        var html_noref = '';

        Swal.fire({
          text: "Transaksi Selesai ?",
          type: 'warning',
          buttons: true,
          dangerMode: true,
          showCancelButton: true,
        })
        .then((willDelete) => {
          if (willDelete.value == true) {

            if(id_payment_method == '2'){
              if($('#no_reference').val() == ""){
                Swal.fire({
                  text: "No Reference Tidak Boleh kosong",
                  type: 'error',
                  buttons: true,
                  dangerMode: true
                });
                return false;
              }
            }


            var ff;
            ff = new FormData();
            ff.append('checkout_code', checkout_code);
            ff.append('id_payment_method', id_payment_method);
            ff.append('no_reference', $('#no_reference').val());
            ff.append('_token', '{{ csrf_token() }}');
            $.ajax({
              data: ff,
              url: "{{ route('Pesanan.teruskanPesanan') }}",
              type: "POST",
              dataType: 'json',
              processData: false,
              contentType: false,
              beforeSend: function () {
                $("#savebutton").html("");
                $("#savebutton").append("<button class='btn btn-primary'><i class='fas fa-spinner fa-spin'></i></button>");
              },
              success: function (data) {
                table.draw();
                console.log(data);
                if(data.state == '0'){
                  $('#savebutton').html('');
                  $('#savebutton').html('<div class="row col-12"><button type="button" class="btn btn-success btn-sm viewstruk" data-idcheckout="'+ data.checkout_code +'"><i class="fas fa-clipboard-check"></i> Transaksi Selesai <i class="fas fa-check-circle"></i></button></div>')
                }else{
                  Swal.fire({
                    text: "No Reference Sudah Ada",
                    type: 'warning',
                  });

                  $('#savebutton').html('');
                  html_noref += '<div class="form-group row col-12">\
                              <input type="hidden" value="'+id_payment_method+'" id="id_payment_method">\
                              <div class="col-sm-4">\
                                <input type="text" class="form-control" placeholder="No Reference" id="no_reference">\
                              </div>\
                              <div class="col-sm-3">\
                                <select class="form-control" id="kurirpengantar">\
                                  <option value="2">Transaksi Selesai</option>\
                                </select>\
                              </div>\
                              <div class="col-sm-5">\
                                  <button type="button" class="btn btn-success btn-sm viewstruk" data-idcheckout="'+ checkout_code +'"><i class="fas fa-eye"></i> Lihat Pembayaran </button>\
                                  <button type="button" class="btn bg-gradient-primary btn-sm" id="teruskanPesanan" data-checkout_code='+checkout_code+'>Update Status</button>\
                                </div>\
                              </div>';
                  $('#savebutton').append(html_noref);
                }
                
              },
              error: function (data) {
                console.log(data);
                $('#savebutton').html('');
                html_noref += '<div class="form-group row col-12">\
                              <input type="hidden" value="'+id_payment_method+'" id="id_payment_method">\
                              <div class="col-sm-4">\
                                <input type="text" class="form-control" placeholder="No Reference" id="no_reference">\
                              </div>\
                              <div class="col-sm-3">\
                                <select class="form-control" id="kurirpengantar">\
                                  <option value="2">Transaksi Selesai</option>\
                                </select>\
                              </div>\
                              <div class="col-sm-5">\
                                  <button type="button" class="btn btn-success btn-sm viewstruk" data-idcheckout="'+ checkout_code +'"><i class="fas fa-eye"></i> Lihat Pembayaran </button>\
                                  <button type="button" class="btn bg-gradient-primary btn-sm" id="teruskanPesanan" data-checkout_code='+checkout_code+'>Update Status</button>\
                                </div>\
                              </div>';
                  $('#savebutton').append(html_noref);
              }
            });
          }
        });

      });

      $('body').on('click', '#perbaruiPesanan', function () {
        table.draw();
      });

      $('body').on('click', '.viewstruk', function () {
        var idcheckout = $(this).data('idcheckout');
        $('#ImgBuktiPembayaran').html("");
        $.get("{{ url('pesanan/viewstruk') }}" + "/" + idcheckout, function (data) {
          console.log(data.photo_payment);
          $('#BuktiPembayaran').modal({backdrop: 'static', keyboard: false});
          $('#ImgBuktiPembayaran').html('<img src="{{ asset('service/public/uploads/struk/') }}/'+ data.photo_payment +'" max-width="100%">');
        });
      });

    });
  
  </script>


@endsection