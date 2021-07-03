@extends('layouts.home')

@section('content')
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
      </div>
      
      <div class="card-body">
        
        <div class="row">
          <button class="btn bg-gradient-primary btn-sm" id="TambahPengguna">Tambah Bank</button>
        </div>
        <br>
        <div class="table-responsive">
          <table id="TblPengguna" class="table table-sm table-bordered table-striped" width="100%">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
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
                      <label for="inputEmail3" class="col-sm-3 col-form-label">Nama</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" id="nama" autocomplete="off">
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
        ajax: "{{ route('Bank.scopedata') }}",
        columns: [
            {data: 'DT_RowIndex'},
            {data: 'nama'},
            {data: 'cekstatus'},
            {data: 'action', className: 'uniqueClassName'}
        ]
      });

      $('body').on('click', '#TambahPengguna', function () {
        $('#titleform').html("");
        $('#icon').html("");
        $('#titleform').html("Tambah Bank");
        $('#AddPengguna').trigger("reset");
        $('#id').val('');
        $('#FormTambahPengguna').modal({backdrop: 'static', keyboard: false});
      });

      $('body').on('click', '.Edit', function () {
        var id = $(this).data('id');
        $('#titleform').html("");
        $('#icon').html("");
        $('#titleform').html("Edit Bank");
        $('#AddPengguna').trigger("reset");
        $('#FormTambahPengguna').modal({backdrop: 'static', keyboard: false});
        $.get("{{ url('bank/edit') }}" +'/' + id, function (data) {
          console.log(data)
            $('#id').val(data.id);
            $('#nama').val(data.nama);
            $('#status').val(data.status);            
          })
      });

      $('#simpan').click(function () {
        if($('#nama').val() == ""){
          toastr.error('Nama Bank Harus Terisi')
          return false;
        }

        var fd;
        fd = new FormData();
        fd.append('id', $('#id').val());
        fd.append('nama', $('#nama').val());
        fd.append('status', $('#status').val());
        fd.append('_token', '{{ csrf_token() }}');
        $.ajax({
          data: fd,
          url: "{{ route('Bank.store') }}",
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
                  $.get("{{ url('bank/destroy')}}" +"/"+ id,function(data){
                      toastr.success('Data Berhasil Dihapus')
                      table.draw();
                  });
              }
          });

      });


    });
  </script>


@endsection