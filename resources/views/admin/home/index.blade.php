@extends('layouts.home')

@section('content')
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
      </div>
      
      <div class="card-body">
        
        <div class="row">
          <button class="btn bg-gradient-primary btn-sm" id="TambahPengguna">Tambah Pengguna</button>
        </div>
        <br>
        <div class="table-responsive">
          <table id="TblPengguna" class="table table-sm table-bordered table-striped" width="100%">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Level</th>
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
              <div class="modal-body" style="height: 80vh; overflow-y: auto;">
                <form id="AddPengguna">
                    <input type="hidden" name="id" id="id">

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-3 col-form-label">Nama</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" id="name" autocomplete="off">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-3 col-form-label">Email</label>
                      <div class="col-sm-9">
                        <input type="email" class="form-control" id="email" autocomplete="off">
                        <input type="hidden" name="check_email" id="check_email">
                        <span id='messageemail'></span>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-3 col-form-label">Akses</label>
                      <div class="col-sm-3">
                        <select class="form-control" id="level">
                          <option value="1">Admin</option>
                          <option value="5">Toko</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-3 col-form-label">Telp</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" id="telp" autocomplete="off">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-3 col-form-label">Alamat</label>
                      <div class="col-sm-9">
                        <textarea id="address" class="form-control"></textarea>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-3 col-form-label">Foto</label>
                      <div class="col-sm-6 custom-file">
                        <input type="file" class="custom-file-input" id="photo">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                      </div>
                      <div class="col-sm-3">
                        <div id="fotophoto"></div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-3 col-form-label">Foto KTP</label>
                      <div class="col-sm-6 custom-file">
                        <input type="file" class="custom-file-input" id="ktp">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                      </div>
                      <div class="col-sm-3">
                        <div id="fotoktp"></div>
                      </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-form-label col-lg-3">Password </label>
                        <div class="col-lg-9">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="off" value="" required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-form-label col-lg-3">Confirm Password </label>
                        <div class="col-lg-9">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" autocomplete="off" value="" required="">
                            <span id='message'></span>
                        </div>
                    </div>

                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-3 col-form-label">Status</label>
                      <div class="col-sm-3">
                        <select class="form-control" id="status">
                          <option value="1">Aktif</option>
                          <option value="0">Non AKtif</option>
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

    $(document).ready(function(){
      $("#email").change(function() {
        var ff;
        ff = new FormData();
        ff.append('email', $(this).val());
        ff.append('_token', '{{ csrf_token() }}');
        $.ajax({
          data: ff,
          url: "{{ route('User.cekEmail') }}",
          type: "POST",
          dataType: 'json',
          processData: false,
          contentType: false,
          beforeSend: function () {
            $("#messageemail").html("");
            $("#messageemail").append("<button class='btn btn-primary'><i class='fas fa-spinner fa-spin'></i></button>");
          },
          success: function (data) {
            console.log(data);
            $("#check_email").val(data);
            $("#messageemail").html("");
            if(data == 1){
              $('#messageemail').html(" <p class='text-danger text-sm'><i class='fas fa-times'></i>Email Sudah Ada ! </p>");
            }else{
              $('#messageemail').html(" <p class='text-success text-sm'><i class='fas fa-check'></i></p>");
            }
          },
          error: function (data) {
            $("#loading").html("");
           console.log('error : '+data);
          }
        });
      });
    });

    $('#password, #confirm_password').on('keyup', function () {
      if ($('#password').val() == $('#confirm_password').val()) {
          $('#message').html('Matching').css('color', 'green');
      } else 
          $('#message').html('Not Matching').css('color', 'red');
      }
    );

    {{-- <i class="fas fa-spinner fa-spin"></i> --}}
    $(function () {

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      var table = $('#TblPengguna').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('User.scopedata') }}",
        columns: [
            {data: 'DT_RowIndex'},
            {data: 'name'},
            {data: 'email'},
            {data: 'level'},
            {data: 'status'},
            {data: 'action', className: 'uniqueClassName'}
        ]
      });

      $('body').on('click', '#TambahPengguna', function () {
        $('#titleform').html("");
        $('#titleform').html("Tambah Pengguna");
        $('#fotoktp').html("");
        $('#messageemail').html("");
        $('#fotophoto').html("");
        $('#AddPengguna').trigger("reset");
        $('#id').val('');
        $('#FormTambahPengguna').modal({backdrop: 'static', keyboard: false});
      });

      $('body').on('click', '.Edit', function () {
        var id = $(this).data('id');
        $('#titleform').html("");
        $('#fotoktp').html("");
        $('#fotophoto').html("");
        $('#titleform').html("Edit Pengguna");
        $('#AddPengguna').trigger("reset");
        $('#FormTambahPengguna').modal({backdrop: 'static', keyboard: false});
        $.get("{{ url('user/edit') }}" +'/' + id, function (data) {
          console.log(data)
          $('#id').val(data.id);
          $('#name').val(data.name);
          $('#email').val(data.email);
          $('#level').val(data.id_level);
          $('#telp').val(data.telp);
          $('#address').val(data.address);
          $('#status').val(data.status);
          $('#fotoktp').html('<a href="{{ asset('service/public/uploads/ktp/') }}/'+ data.ktp +'" data-toggle="lightbox" data-gallery="gallery"><img src="{{ asset('service/public/uploads/ktp/') }}/'+ data.ktp +'" class="product-image"></a>');
          $('#fotophoto').html('<a href="{{ asset('service/public/uploads/photo/') }}/'+ data.photo +'" data-toggle="lightbox"  data-gallery="gallery"><img src="{{ asset('service/public/uploads/photo/') }}/'+ data.photo +'" class="product-image"></a>');
        })
      });

      $('#simpan').click(function () {
        if($('#check_email').val() == "1"){
          toastr.error('Email Sudah Tersedia')
          return false;
        }

        if($('#name').val() == ""){
          toastr.error('Nama Harus Terisi')
          return false;
        }

        if($('#email').val() == ""){
          toastr.error('Email Harus Terisi')
          return false;
        }

        if($('#telp').val() == ""){
          toastr.error('Telp Harus Terisi')
          return false;
        }

        if($('#address').val() == ""){
          toastr.error('Alamat Harus Terisi')
          return false;
        }

        if($('#level').val() == ""){
          toastr.error('Akses Harus Terisi')
          return false;
        }

        if($('#status').val() == ""){
          toastr.error('Status Harus Terisi')
          return false;
        }

        if($('#password').val() != $('#confirm_password').val()){
          toastr.error('Password Tidak Sama')
          return false;
        }

        if(($('#id').val() == "") && ($('#password').val() == "") && ($('#confirm_password').val() == "")){
          toastr.error('Password Harus Terisi')
          return false; 
        }

        var obj_photo = $('#photo').get(0).files.length;
        if(obj_photo > 0){
          var file_data = $('#photo').prop('files')[0];
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

        var obj_ktp = $('#ktp').get(0).files.length;
        if(obj_ktp > 0){
          var file_ktp = $('#ktp').prop('files')[0];
          var tipe_ktp = file_ktp.type;
          var ValidImageTypes_ktp = ["image/jpg", "image/jpeg", "image/png"];
          var file_size_ktp = file_ktp.size / 1000;

          if($.inArray(tipe_ktp, ValidImageTypes_ktp) < 0){
            toastr.error('Format File Harus jpe, jpeg, atau jpg')
            return false;
          }

          if(file_size_ktp >= 2000){
            toastr.error('Ukuran File Harus Dibawah 2Mb')
            return false;
          }

        }

        var fd;
        fd = new FormData();
        fd.append('id', $('#id').val());
        fd.append('name', $('#name').val());
        fd.append('email', $('#email').val());
        fd.append('level', $('#level').val());
        fd.append('telp', $('#telp').val());
        fd.append('status', $('#status').val());
        fd.append('address', $('#address').val());
        fd.append('password', $('#password').val());
        fd.append('obj_photo', obj_photo);
        fd.append('photo', file_data);
        fd.append('obj_ktp', obj_ktp);
        fd.append('ktp', file_ktp);
        fd.append('_token', '{{ csrf_token() }}');
        $.ajax({
          data: fd,
          url: "{{ route('User.store') }}",
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
            /*if(data.meta == 400){
              $("#loading").html("");
              $('#FormTambahPengguna').modal('hide');
              table.draw();
              toastr.error('Gagal Menyimpan Data')
            }else{
              $("#loading").html("");
              $('#FormTambahPengguna').modal('hide');
              table.draw();
              toastr.success('Data Berhasil Disimpan')
            }*/
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
                  $.get("{{ url('user/destroy')}}" +"/"+ id,function(data){
                      toastr.success('Data Berhasil Dihapus')
                      table.draw();
                  });
              }
          });

      });


    });
  </script>


@endsection