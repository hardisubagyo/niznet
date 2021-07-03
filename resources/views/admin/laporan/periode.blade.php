<!DOCTYPE html>
<html>
<head>

  <style type="text/css">
    body{
      font-family: sans-serif;
    }
    table{
      margin: 20px auto;
      border-collapse: collapse;
    }
    table th,
    table td{
      border: 1px solid #3c3c3c;
      padding: 3px 8px;
   
    }
    a{
      background: blue;
      color: #fff;
      padding: 8px 10px;
      text-decoration: none;
      border-radius: 2px;
    }
  </style>

</head>
<body>

  @php
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".date('YMDhis').".xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
  @endphp

  <table border="1">
    <thead>
      <tr>
        <th>No</th>
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
    <tbody>
      @php $no = 1; @endphp
      @foreach($get as $get)
        <?php
          if($get->status == '1'){
            $sts = "Menunggu Pembayaran";
          }else if($get->status == '2'){
            $sts = "Menunggu Verifikasi";
          }else{
            $sts = "Transaksi Selesai";
          }
        ?>
        <tr>
          <td>{{ $no++ }}</td>
          <td>{{ $get->created_at }}</td>
          <td>{{ $get->namatoko }}</td>
          <td>{{ $get->name }}</td>
          <td>{{ $get->product_name }}</td>
          <td>{{ $get->brand_name }}</td>
          <td>{{ $get->category_name }}</td>
          <td>{{ $get->quantity }}</td>
          <td>{{ number_format($get->price,0,'.','.') }}</td>
          <td>{{ number_format($get->total,0,'.','.') }}</td>
          <td>{{ $sts }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  
</body>
</html>