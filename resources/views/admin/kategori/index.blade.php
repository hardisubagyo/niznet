@extends('layouts.home')

@section('content')
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
      </div>
      
      <div class="card-body">
        
        <div class="row">
          <button class="btn bg-gradient-primary btn-sm" id="TambahPengguna">Tambah Kategori</button>
        </div>
        <br>
        <div class="table-responsive">
          <table id="TblPengguna" class="table table-sm table-bordered table-striped" width="100%">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Nama Variasi</th>
                <th>Icon</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>  
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
              <div class="modal-body">
                <form id="AddPengguna">
                    <input type="hidden" name="id" id="id">

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-3 col-form-label">Nama Kategori</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" id="category_name" autocomplete="off">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-3 col-form-label">Variasi</label>
                      <div class="col-sm-9">
                        <select class="form-control" id="id_variasi"></select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-3 col-form-label">Icon</label>
                      <div class="col-sm-6 custom-file">
                        <input type="file" class="custom-file-input" id="category_icon">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                      </div>
                      <div class="col-sm-3">
                        <div id="icon"></div>
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
        ajax: "{{ route('Kategori.scopedata') }}",
        columns: [
            {data: 'DT_RowIndex'},
            {data: 'category_name'},
            {data: 'namavariasi'},
            {data: 'icon'},
            {data: 'cekstatus'},
            {data: 'action', className: 'uniqueClassName'}
        ]
      });

      $('body').on('click', '#TambahPengguna', function () {
        $('#titleform').html("");
        $('#icon').html("");
        $('#titleform').html("Tambah Kategori");
        $('#AddPengguna').trigger("reset");
        $('#id').val('');
        $('#FormTambahPengguna').modal({backdrop: 'static', keyboard: false});
        $('#id_variasi').html('');
        $.get("{{ url('kategori/getVariasi') }}", function (data) {
          var html_variasi = '';
          for(var xx=0; xx<data.length; ++xx){
            html_variasi += '<option value="'+data[xx].id+'">'+ data[xx].name +'</option>';
          }
          $('#id_variasi').append(html_variasi);
        })
      });

      $('body').on('click', '.Edit', function () {
        var id = $(this).data('id');
        $('#titleform').html("");
        $('#icon').html("");
        $('#titleform').html("Edit Kategori");
        $('#AddPengguna').trigger("reset");
        $('#FormTambahPengguna').modal({backdrop: 'static', keyboard: false});
        $('#id_variasi').html('');
        $.get("{{ url('kategori/getVariasi') }}", function (data) {
          var html_variasi = '';
          for(var xx=0; xx<data.length; ++xx){
            html_variasi += '<option value="'+data[xx].id+'">'+ data[xx].name +'</option>';
          }
          $('#id_variasi').append(html_variasi);
        })

        $.get("{{ url('kategori/edit') }}" +'/' + id, function (data) {
          console.log(data)
          $('#id').val(data.id);
          $('#category_name').val(data.category_name);
          $('#id_variasi').val(data.id_variasi);
          $('#status').val(data.status);
          $('#icon').html('<a href="{{ asset('service/public/uploads/category/') }}/'+ data.category_icon +'" data-toggle="lightbox" data-gallery="gallery"><img src="{{ asset('service/public/uploads/category/') }}/'+ data.category_icon +'" class="product-image"></a>');


        })
      });

      $('#simpan').click(function () {
        if($('#category_name').val() == ""){
          toastr.error('Nama Kategori Harus Terisi')
          return false;
        }

        var obj_category_icon = $('#category_icon').get(0).files.length;
        if(obj_category_icon > 0){
          var file_data = $('#category_icon').prop('files')[0];
          var tipe_foto = file_data.type;
          var ValidImageTypes = ["image/jpg", "image/jpeg", "image/png"];
          var file_size = file_data.size / 1000;

          if($.inArray(tipe_foto, ValidImageTypes) < 0){
            toastr.error('Format File Harus jpe, jpeg, atau jpg')
            return false;
          }

          if(file_size >= 2000){
            toastr.error('Ukuran File Harus Dibawah 2Mb')
            return false;
          }

        }

        var fd;
        fd = new FormData();
        fd.append('id', $('#id').val());
        fd.append('category_name', $('#category_name').val());
        fd.append('id_variasi', $('#id_variasi').val());
        fd.append('status', $('#status').val());
        fd.append('obj_category_icon', obj_category_icon);
        fd.append('category_icon', file_data);
        fd.append('_token', '{{ csrf_token() }}');
        $.ajax({
          data: fd,
          url: "{{ route('Kategori.store') }}",
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
            table.draw();
            toastr.success('Data Berhasil Disimpan')
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
                  $.get("{{ url('kategori/destroy')}}" +"/"+ id,function(data){
                      toastr.success('Data Berhasil Dihapus')
                      table.draw();
                  });
              }
          });

      });


    });
  </script>


@endsection