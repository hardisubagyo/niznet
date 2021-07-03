@extends('layouts.home')

@section('content')
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
      </div>
      
      <div class="card-body">
        <div class="table-responsive">
          <table id="TblPengguna" class="table table-sm table-bordered table-striped" width="100%">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telp</th>
                <th>Alamat</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>  
        </div>
        
        <div class="modal fade" id="FormCart">
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
                      <th>Nama</th>
                      <th>Produk</th>
                      <th>Jumlah</th>
                      <th>Harga</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody id="ListCart"></tbody>
                  <tfoot>
                    <tr>
                      <th colspan="5"><b>JUMLAH</b></th>
                      <th id="TotalCart" style="text-align:right"></th>
                    </tr>
                  </tfoot>
                </table>
              </div>
              
              </div>
              <div class="modal-footer right-content-between" id="savebutton">
                <button class="btn btn-warning" data-dismiss="modal">Tutup</button>
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

      var table = $('#TblPengguna').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('Cart.scopedata') }}",
        columns: [
            {data: 'DT_RowIndex'},
            {data: 'name'},
            {data: 'email'},
            {data: 'telp'},
            {data: 'address'},
            {data: 'action', className: 'uniqueClassName'}
        ]
      });

      $('body').on('click', '.View', function () {
        var id = $(this).data('id');
        $.get("{{ url('cart/edit') }}" +'/' + id, function (data) {
          console.log(data.total[0].total)
          $('#titleform').html("");
          $('#titleform').html("Data Keranjang");
          $('#AddPengguna').trigger("reset");
          $('#FormCart').modal({backdrop: 'static', keyboard: false});
          $('#ListCart').html('');
          $('#TotalCart').html('');
          var html = '';
          for(var x=0; x<data.list.length; ++x){
            var no = x + 1;
            html += '<tr><td>'+ no +'</td><td>'+data.list[x].name+'</td><td>'+data.list[x].product_name+'</td><td>'+data.list[x].quantity+'</td><td style="text-align:right">'+ numeral(data.list[x].price).format('0,0')+'</td><td style="text-align:right">'+numeral(data.list[x].total).format('0,0')+'</td></tr>';
          }
          $('#ListCart').append(html);
          $('#TotalCart').html('<b>'+numeral(data.total[0].total).format('0,0')+'</b>');
        })
      });

    });
  
  </script>


@endsection