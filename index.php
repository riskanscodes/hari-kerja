<?php
require_once __DIR__ . '/vendor/autoload.php';

use Cmixin\BusinessDay;
use Carbon\Carbon;
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

  <title>Hitung Hari Kerja</title>
</head>

<body>
  <style>
    .container {
      margin-top: 20px;
    }

    .card,
    .alert {
      width: 25em;
      margin: 0 auto;
    }
  </style>
  <div class="container">
    <div class="card">
      <div class="card-header text-center">Program Menentukan Hari Kerja</div>
      <div class="card-body">
        <form action="" method="post">
          <div class="mb-3">
            <input type="date" class="form-control" name="tgl_mulai" required>
          </div>
          <div class="mb-3">
            <input type="date" class="form-control" name="tgl_selesai" required>
          </div>
          <button type="submit" name="hitung" class="btn btn-primary">Hitung</button>
        </form>
      </div>
    </div>
    <?php
    

    if (isset($_POST['hitung'])) {
      $tglMulai = $_POST['tgl_mulai'];
      $tglSelesai = $_POST['tgl_selesai'];

      $baseList = 'id-id';
      $additionalHolidays = [];

      BusinessDay::enable('Carbon\Carbon', $baseList, $additionalHolidays);
      $jumlahHariKerja = Carbon::parse($tglMulai)->diffInBusinessDays(Carbon::parse($tglSelesai)->endOfDay());
    ?>

      <div class="card mt-3">
        <div class="card-header text-center">Jumlah Hari Kerja</div>
        <div class="card-body">
          <?php echo $jumlahHariKerja ?> hari kerja
        </div>
      </div>

    <?php
    }
    ?>

  </div>

  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</body>
</html>
<?php
// //default time zone
date_default_timezone_set("Asia/Jakarta");

$start_date = '2022-09-05';
$end_date       = '2022-09-09';

echo "Tanggal Mulai : ".$start_date."<br>";
echo "Tanggal Akhir : ".$end_date."<br>";
echo "Selisih : ".selisih_hari($start_date, $end_date)." Hari <br>";
echo "Selisih hari dengan tidak menghitung hari Sabtu, Minggu, dan Libur Nasional";



//fungsi check tanggal merah
function selisih_hari($start_date, $end_date) {
    $total_hari = 0;
    $period         = new DatePeriod(
                          new DateTime($start_date),
                          new DateInterval('P1D'),
                          new DateTime(date('Y-m-d', strtotime('+1 day', strtotime($end_date)))));

    foreach ($period as $period) {
        $date = $period->format('Ymd');
        $array = json_decode(file_get_contents("https://raw.githubusercontent.com/guangrei/Json-Indonesia-holidays/master/calendar.json"),true);

        if(isset($array[$date])) {
            //jika tanggal merah berdasarkan libur nasional
        } else if(date("D",strtotime($date))==="Sun") {
            //jika Hari Minggu
        }   else if(date("D",strtotime($date))==="Sat") {
            //jika Hari Sabtu
        } else {
            //jika Bukan Tanggal Merah dan Libur Nasional
            $total_hari++;
        }
    }

    return $total_hari;
}

