@extends('layouts.home')

@section('content')
  
  <link rel="stylesheet" href="{{ asset('public/tag/dist/bootstrap-tagsinput.css') }} ">
  <link rel="stylesheet" href="{{ asset('public/tag/assets/app.css') }}">
  <script src="{{ asset('public/tag/dist/bootstrap-tagsinput.min.js') }}"></script>
  <script src="{{ asset('public/tag/dist/bootstrap-tagsinput/bootstrap-tagsinput-angular.min.js') }}"></script>
  <script src="{{ asset('public/tag/assets/app.js') }}"></script>
  <script src="{{ asset('public/tag/assets/app_bs3.js') }}"></script>

  
  <style type="text/css"></style>

  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
      </div>
      
      <div class="card-body">
        
        <div class="row">
          <button class="btn bg-gradient-primary btn-sm" id="TambahPengguna">Tambah Produk</button>
          <form class="form-inline ml-3" method="get" action="{{ route('Produk.scopedata') }}">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" name="keyword" type="search" placeholder="Cari Produk" aria-label="Search" value="{{ $keyword }}">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
        <br>

        <div class="row d-flex align-items-stretch">
          @foreach($data as $item)
            <div class="col-md-3">
              @php
                if($item->stock_produk_toko <= 10){
                  $alert = 'bg-gradient-danger';
                }else{
                  $alert = '';
                }
              @endphp
              <div class="card {{ $alert }}" style="height: 450px;">
                <a href="{{ asset('service/public/uploads/produk/'.$item->image1) }}" data-toggle="lightbox">
                  <center>
                    <img src="{{ asset('service/public/uploads/produk/'.$item->image1) }}" style="max-height: 150px; max-width: 100%;" >
                  </center>
                </a>

                <div class="card-body table-responsive p-0" style="height: 150px;">
                  <hr>
                  <table class="table table-sm table-striped">
                    <tbody>
                      <tr>
                        <td><b>Kode</b></td>
                        <td>{{$item->product_code}}</td>
                      </tr>
                      <tr>
                        <td><b>Nama</b></td>
                        <td>{{$item->product_name}}</td>
                      </tr>
                      <tr>
                        <td><b>Stock Gudang</b></td>
                        <td>{{ number_format($item->stock,0,'.','.') }}</td>
                      </tr>
                      <tr>
                        <td><b>Stock Toko</b></td>
                        <td>{{ number_format($item->stock_produk_toko,0,'.','.') }}</td>
                      </tr>
                      <tr>
                        <td><b>Harga</b></td>
                        <td>{{ number_format($item->price,0,'.','.') }}</td>
                      </tr>
                      <tr>
                        <td><b>Variasi</b></td>
                        <td>
                          {{-- {{$item->variasi}} --}}
                          <?php

                            $exp = $item->variasi;
                            $explode = explode(",", $exp);
                            if(!empty($item->variasi)){
                              for($r=0;$r<count((array)$explode);$r++){
                                echo "<small class='badge badge-warning'>#".$explode[$r]." </small> ";
                              }
                            }else{}
                            
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td><b>Status</b></td>
                        <td>
                          @if($item->status_produk_toko == '1')
                            Aktif
                          @else
                            Non Aktif
                          @endif
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                
                <div class="card-footer">
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-primary Info" title="Detail Produk" data-toggle="tooltip" data-id="{{ $item->id }}">
                      <i class="fas fa-info-circle"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-warning Edit" title="Update Produk" data-toggle="tooltip" data-id="{{ $item->id }}">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger Delete" title="Hapus Produk" data-toggle="tooltip" data-id="{{ $item->id }}">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </div>

              </div>
            </div>
          @endforeach
          
        </div>

        <div class="card-footer">
          <nav aria-label="Contacts Page Navigation">
            {{ $data->links() }}
          </nav>
        </div>
        
        <div class="modal fade" id="FormTambahPengguna">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="titleform"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style="height: 50vh; overflow-y: auto;">
                <form id="AddPengguna">
                    <input type="hidden" name="id" id="id">

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-3 col-form-label">Produk</label>
                      <div class="col-sm-9">
                        <select name="produk" id="produk" class="form-control">
                          @foreach($produk as $items)
                            <option value="{{ $items->id }}">{{ $items->product_code ." - ". $item->product_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-3 col-form-label">Stok Toko</label>
                      <div class="col-sm-3">
                        <input type="number" class="form-control" id="stock" autocomplete="off">
                      </div>
                    </div> 

                    
                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-3 col-form-label">Status</label>
                      <div class="col-sm-9">
                        <select name="status" id="status" class="form-control">
                          <option value="1">Aktif</option>
                          <option value="0">Non Aktif</option>
                        </select>
                      </div>
                    </div>

                </form>
              </div>
              <div class="modal-footer right-content-between" id="savebutton">
                <button class="btn btn-warning" data-dismiss="modal">Tutup</button>
                <button class="btn btn-primary" id="simpan">Simpan</button>
                <div id="loading"></div>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

        <div class="modal fade" id="FormInfoProduk">
          <div class="modal-dialog" style="max-width: 90%;">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style="height: 80vh; overflow-y: auto;">
                <div class="row">
                  <div class="col-12 col-sm-6">
                    <div class="col-12" id="info_image1"></div>
                    <div class="col-12 product-image-thumbs">
                      <div class="product-image-thumb active" id="info_image2"></div>
                      <div class="product-image-thumb" id="info_image3"></div>
                      <div class="product-image-thumb" id="info_image4"></div>
                      <div class="product-image-thumb" id="info_image5"></div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <table class="table table-striped">
                      <tbody>
                        <tr>
                          <td width="20%"><b>Nama</b></td>
                          <td id="info_product_name"></td>
                        </tr>
                        <tr>
                          <td width="20%"><b>Kode</b></td>
                          <td id="info_product_code"></td>
                        </tr>
                        <tr>
                          <td width="20%"><b>Kategori</b></td>
                          <td id="info_category_code"></td>
                        </tr>
                        <tr>
                          <td width="20%"><b>Merk</b></td>
                          <td id="info_brand_product"></td>
                        </tr>
                        <tr>
                          <td width="20%"><b>Harga</b></td>
                          <td id="info_price"></td>
                        </tr>
                        <tr>
                          <td width="20%"><b>Stok</b></td>
                          <td id="info_stock"></td>
                        </tr>
                        <tr>
                          <td width="20%"><b>Variasi</b></td>
                          <td id="info_variasi"></td>
                        </tr>
                        <tr>
                          <td width="20%"><b>Deskripsi</b></td>
                          <td id="info_desc"></td>
                        </tr>
                        <tr>
                          <td width="20%"><b>Status</b></td>
                          <td id="info_status"></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      
    </div>
    
  </div>


  <script>

    $(document).ready(function(){

      $("#product_code").change(function() {
        var ff;
        ff = new FormData();
        ff.append('product_code', $(this).val());
        ff.append('_token', '{{ csrf_token() }}');
        $.ajax({
          data: ff,
          url: "{{ route('Produk.checkCodeProduct') }}",
          type: "POST",
          dataType: 'json',
          processData: false,
          contentType: false,
          beforeSend: function () {
            $("#message").html("");
            $("#message").append("<button class='btn btn-primary'><i class='fas fa-spinner fa-spin'></i></button>");
          },
          success: function (data) {
            console.log(data);
            $("#check_code").val(data);
            $("#message").html("");
            if(data == 1){
              $('#message').html(" <p class='text-danger text-sm'><i class='fas fa-times'></i> Kode Product Sudah Ada ! </p>");
            }else{
              $('#message').html(" <p class='text-success text-sm'><i class='fas fa-check'></i></p>");
            }
          },
          error: function (data) {
            $("#loading").html("");
           console.log('error : '+data);
          }
        });
      });
    });

    $(function () {

      /*$('#desc').summernote();*/

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $('body').on('click', '#TambahPengguna', function () {
        $('#titleform').html("");
        $('#titleform').html("Tambah Produk");
        $('#AddPengguna').trigger("reset");
        $('#id').val('');
        $('#desc').val('');
        $('#icon_image1').html("");
        $('#icon_image2').html("");
        $('#icon_image3').html("");
        $('#icon_image4').html("");
        $('#icon_image5').html("");
        $('#FormTambahPengguna').modal({backdrop: 'static', keyboard: false});
      });

      $('#simpan').click(function () {

        

        if($('#stock').val() == ""){
          toastr.error('Stok Harus Terisi')
          return false;
        }


        var fd;
        fd = new FormData();
        fd.append('id', $('#id').val());
        fd.append('produk', $('#produk').val());
        fd.append('stock', $('#stock').val());
        fd.append('status', $('#status').val());

        fd.append('_token', '{{ csrf_token() }}');
        $.ajax({
          data: fd,
          url: "{{ route('ProdukToko.store') }}",
          type: "POST",
          dataType: 'json',
          processData: false,
          contentType: false,
          beforeSend: function () {
            $("#loading").html("");
            $("#loading").append("<button class='btn btn-primary'><i class='fas fa-spinner fa-spin'></i></button>");
          },
          success: function (data) {
            console.log(data);
            $("#loading").html("");
            $('#FormTambahPengguna').modal('hide');
            toastr.success('Data Berhasil Disimpan')
            location.reload();
          },
          error: function (data) {
            $("#loading").html("");
            toastr.error('Gagal Menyimpan Data')
          }
        });
      });

      $('body').on('click', '.Delete', function () {
          var id = $(this).data('id');
          Swal.fire({
              text: "Anda Yakin ?",
              type: 'warning',
              buttons: true,
              dangerMode: true,
              showCancelButton: true,
          })
          .then((willDelete) => {
              if (willDelete.value == true) {
                  $.get("{{ url('produk/destroy')}}" +"/"+ id,function(data){
                      toastr.success('Data Berhasil Dihapus')
                      location.reload();
                  });
              }
          });
      });

      $('body').on('click', '.Info', function () {
        var id = $(this).data('id');
        $.get("{{ url('produk/show') }}" +'/' + id, function (data) {
          console.log(data);
          $('#FormInfoProduk').modal({backdrop: 'static', keyboard: false});
          $('#info_product_name').html('');
          $('#info_product_code').html('');
          $('#info_category_code').html('');
          $('#info_brand_product').html('');
          $('#info_price').html('');
          $('#info_stock').html('');
          $('#info_variasi').html('');
          $('#info_desc').html('');
          $('#info_image1').html('');
          $('#info_image2').html('');
          $('#info_image3').html('');
          $('#info_image4').html('');
          $('#info_image5').html('');
          $('#info_status').html('');

          $('#info_product_name').html(data.product_name);
          $('#info_product_code').html(data.product_code);
          $('#info_category_code').html(data.category_name);
          $('#info_brand_product').html(data.brand_name);
          $('#info_price').html(data.price);
          $('#info_stock').html(data.stock);
          $('#info_variasi').html(data.variasi);
          $('#info_desc').html(data.desc);
          $('#info_image1').html('<a href="{{ asset('service/public/uploads/produk/') }}/'+ data.image1 +'" data-toggle="lightbox"  data-gallery="gallery"><img src="{{ asset('service/public/uploads/produk/') }}/'+ data.image1 +'" class="product-image"></a>');

          $('#info_image2').html('<a href="{{ asset('service/public/uploads/produk/') }}/'+ data.image2 +'" data-toggle="lightbox"  data-gallery="gallery"><img src="{{ asset('service/public/uploads/produk/') }}/'+ data.image2 +'" class="product-image"></a>');

          $('#info_image3').html('<a href="{{ asset('service/public/uploads/produk/') }}/'+ data.image3 +'" data-toggle="lightbox"  data-gallery="gallery"><img src="{{ asset('service/public/uploads/produk/') }}/'+ data.image3 +'" class="product-image"></a>');

          $('#info_image4').html('<a href="{{ asset('service/public/uploads/produk/') }}/'+ data.image4 +'" data-toggle="lightbox"  data-gallery="gallery"><img src="{{ asset('service/public/uploads/produk/') }}/'+ data.image4 +'" class="product-image"></a>');

          $('#info_image5').html('<a href="{{ asset('service/public/uploads/produk/') }}/'+ data.image5 +'" data-toggle="lightbox"  data-gallery="gallery"><img src="{{ asset('service/public/uploads/produk/') }}/'+ data.image5 +'" class="product-image"></a>');

          if(data.status == '1'){
            $('#info_status').html("Aktif");
          }else{
            $('#info_status').html("Non Aktif");
          }
        });

      });

      $('body').on('click', '.Edit', function () {
        var id = $(this).data('id');
        $('#titleform').html("");
        $('#titleform').html("Edit Produk");
        $('#AddPengguna').trigger("reset");
        $('#FormTambahPengguna').modal({backdrop: 'static', keyboard: false});
        $('#icon_image1').html("");
        $('#icon_image2').html("");
        $('#icon_image3').html("");
        $('#icon_image4').html("");
        $('#icon_image5').html("");
        $.get("{{ url('produk/edit') }}" +'/' + id, function (data) {
          $('#id').val(data.id);
          $('#product_code').val(data.product_code);
          $('#product_name').val(data.product_name);
          $('#price').val(data.price);
          $('#stock').val(data.stock);
          $("#variasi").tagsinput('add', data.variasi);
          $('#category_code').val(data.category_code);
          $('#brand_code').val(data.brand_code);
          $('#desc').val(data.desc);
          $('#status').val(data.status);
          $('#icon_image1').html('<a href="{{ asset('service/public/uploads/produk/') }}/'+ data.image1 +'" data-toggle="lightbox"><img src="{{ asset('service/public/uploads/produk/') }}/'+ data.image1 +'" class="product-image"></a>');
          $('#icon_image2').html('<a href="{{ asset('service/public/uploads/produk/') }}/'+ data.image2 +'" data-toggle="lightbox"><img src="{{ asset('service/public/uploads/produk/') }}/'+ data.image2 +'" class="product-image"></a>');
          $('#icon_image3').html('<a href="{{ asset('service/public/uploads/produk/') }}/'+ data.image3 +'" data-toggle="lightbox"><img src="{{ asset('service/public/uploads/produk/') }}/'+ data.image3 +'" class="product-image"></a>');
          $('#icon_image4').html('<a href="{{ asset('service/public/uploads/produk/') }}/'+ data.image4 +'" data-toggle="lightbox"><img src="{{ asset('service/public/uploads/produk/') }}/'+ data.image4 +'" class="product-image"></a>');
          $('#icon_image5').html('<a href="{{ asset('service/public/uploads/produk/') }}/'+ data.image5 +'" data-toggle="lightbox"><img src="{{ asset('service/public/uploads/produk/') }}/'+ data.image5 +'" class="product-image"></a>');
        });
      });


    });
  </script>


@endsection