<!DOCTYPE html>
<html>
<head>
</head>
<body>

  @php
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=abc.xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
  @endphp

  
</body>
</html>