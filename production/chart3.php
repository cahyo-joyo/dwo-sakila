<?php
include "database 3.php";
$conn = mysqli_connect("localhost", "root", "", "whsakila2021");

$sql = "SELECT s.nama_kota, SUM(f.amount) total
FROM store s, fakta_pendapatan f
WHERE s.store_id = f.store_id
GROUP BY s.nama_kota";
$result = mysqli_query($conn, $sql);

$sql1 = "SELECT COUNT(customer_id) cus from fakta_pendapatan";
$totcus = mysqli_query($conn, $sql1);

$sql2 = "SELECT sum(amount) tot FROM fakta_pendapatan";
$totpes = mysqli_query($conn, $sql2);

$sql3 = "SELECT COUNT(DISTINCT customer_id) totpel FROM fakta_pendapatan";
$totpel = mysqli_query($conn, $sql3);

$sql4 = "SELECT COUNT(film_id) film from fakta_pendapatan";
$totfilm = mysqli_query($conn, $sql4);

$sql5 = "SELECT  c.nama, SUM(f.amount) total FROM fakta_pendapatan f JOIN customer c ON (f.customer_id=c.customer_id) GROUP BY f.customer_id ORDER by total DESC LIMIT 5";
$toppen = mysqli_query($conn, $sql5);

$sqlku = "SELECT  c.nama nama , COUNT(f.customer_id) total_beli FROM fakta_pendapatan f JOIN customer c ON ( f.customer_id=c.customer_id) GROUP by f.customer_id ORDER by total_beli DESC LIMIT 5";
$topbel = mysqli_query($conn, $sqlku);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- <link rel="icon" href="images/favicon.ico" type="image/ico" /> -->

  <title>Data Warehouse </title>

  <!-- Highchart -->
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/data.js"></script>
  <script src="https://code.highcharts.com/modules/drilldown.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>

  <!-- Bootstrap -->
  <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">

  <!-- bootstrap-progressbar -->
  <link href="../vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
  <!-- JQVMap -->
  <link href="../vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
  <!-- bootstrap-daterangepicker -->
  <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

  <!-- Custom Theme Style -->
  <link href="../build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">
          <div class="navbar nav_title" style="border: 0;">
            <a href="dashboard.php" class="site_title"><i class="fa fa-paw"></i> <span>Data Warehouse</span></a>
          </div>

          <div class="clearfix"></div>

          <br />

          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
              <ul class="nav side-menu">
                <li>
                  <a href="dashboard.php"><i class="fa fa-bar-chart-o"></i> <span> Chart - DB Whsakila </span></a>
                </li>
                <li>
                  <a href="chart1.php"><i class="fa fa-bar-chart-o"></i> <span> Chart 1 </span></a>
                </li>
                <li>
                  <a href="chart3.php"><i class="fa fa-bar-chart-o"></i> <span> Chart 2 </span></a>
                </li>
                <li>
                  <a href="chart2.php"><i class="fa fa-bar-chart-o"></i> <span> Chart 3 </span></a>
                </li>
              </ul>
            </div>

          </div>
          <!-- /sidebar menu -->

        </div>
      </div>

      <!-- top navigation -->
      <div class="top_nav">
        <div class="nav_menu">
          <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
          </div>
          <nav class="nav navbar-nav">
            <ul class=" navbar-right">
              <li class="nav-item dropdown open" style="padding-left: 15px;">
                <div class="input-group">
                  <a class="dropdown-item" href="index.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                </div>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- /top navigation -->

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <ul class="stats-overview">
            <li>
              <span class="name">
                <h3>Total Pendapatan</h3>
              </span>
              <span class="value text-success">
                <h3> $
                  <?php
                  while ($pes = mysqli_fetch_array($totpes)) {
                    echo $pes['tot'];
                  }
                  ?>
                </h3>
              </span>
            </li>
            <li>
              <span class="name">
                <h3>Total Pelanggan</h3>
              </span>
              <span class="value text-success">
                <h3>
                  <?php
                  while ($cusunik = mysqli_fetch_array($totpel)) {
                    echo $cusunik['totpel'];
                  }
                  ?>
                </h3>
              </span>
            </li>
            <li class="hidden-phone">
              <span class="name">
                <h3>Total Pesanan</h3>
              </span>
              <span class="value text-success">
                <h3>
                  <?php
                  while ($oi = mysqli_fetch_array($totcus)) {

                    echo $oi['cus'];
                  }
                  ?>
                </h3>
              </span>
            </li>
          </ul>
          <br />


          <div class="row">
            <div class="col-12">
              <div class="card-box">
                <div class="m-t-30" id="container"></div>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-4">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Total Pendapatan Setiap Kota</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                      <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table class="table">
                    <th>Nama Kota</th>
                    <th>Total Pendapatan</th>

                    <?php
                    while ($hadir = mysqli_fetch_array($result)) {
                      echo "<tr>";
                      echo "<td>" . $hadir['nama_kota'] . "</td>";
                      echo "<td>$ " . $hadir['total'] . "</td>";
                      echo "</tr>";
                    }
                    ?>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="x_panel">
                <div class="x_title">
                  <h2>5 Pelanggan Palig Sering Transaksi</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                      <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table class="table">
                    <th>Nama Pelanggan</th>
                    <th>Total Transaksi</th>

                    <?php
                    while ($top = mysqli_fetch_array($toppen)) {
                      echo "<tr>";
                      echo "<td>" . $top['nama'] . "</td>";
                      echo "<td>$ " . $top['total'] . "</td>";
                      echo "</tr>";
                    }
                    ?>
                  </table>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="x_panel">
                <div class="x_title">
                  <h2>5 Pelanggan paling sering Beli</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                      <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table class="table">
                    <th>Nama Pelanggan</th>
                    <th>Total Pembelian</th>

                    <?php
                    while ($tcust = mysqli_fetch_array($topbel)) {
                      echo "<tr>";
                      echo "<td>" . $tcust['nama'] . "</td>";
                      echo "<td> " . $tcust['total_beli'] . " kali</td>";
                      echo "</tr>";
                    }
                    ?>
                  </table>
                </div>
              </div>
            </div>

          </div>


          <div class="row">
            <div class="col-12">
              <div class="card-box" style="height: 500px;">
                <iframe name="mondrian" src="http://localhost:8080/mondrian/index.html" style="height:100%; width:100%; border:none; align-content:center"> </iframe>
              </div>
            </div>
          </div>


        </div>
      </div>


      <script type="text/javascript">
        // Create the chart
        Highcharts.chart('container', {
          chart: {
            type: 'pie'
          },
          title: {
            text: 'Persentase Nilai Penjualan (WH Sakila) - Berdasarkan Toko'
          },
          subtitle: {
            text: 'Klik di potongan kue untuk melihat detil nilai penjualan setiap toko berdasarkan kategori'
          },

          accessibility: {
            announceNewData: {
              enabled: true
            },
            point: {
              valueSuffix: '%'
            }
          },

          plotOptions: {
            series: {
              dataLabels: {
                enabled: true,
                format: '{point.name}: {point.y:.1f}%'
              }
            }
          },

          tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total</br>'
          },

          series: [{
            name: "Pendapatan By Toko",
            colorByPoint: true,
            data: <?php

                  $datanya = $json_all_kat;
                  $data1 = str_replace('["', '{"', $datanya);
                  $data2 = str_replace('"]', '"}', $data1);
                  $data3 = str_replace('[[', '[', $data2);
                  $data4 = str_replace(']]', ']', $data3);
                  $data5 = str_replace(':', '" : "', $data4);
                  $data6 = str_replace('"name"', 'name', $data5);
                  $data7 = str_replace('"drilldown"', 'drilldown', $data6);
                  $data8 = str_replace('"y"', 'y', $data7);
                  $data9 = str_replace('",', ',', $data8);
                  $data10 = str_replace(',y', '",y', $data9);
                  $data11 = str_replace(',y : "', ',y : ', $data10);
                  echo $data11;
                  ?>

          }],
          drilldown: {
            series: [

              <?php
              //TEKNIK CLEAN
              echo $string_data;

              ?>



            ]
          }
        });
      </script>

      <!-- /page content -->

      <!-- footer content -->
      <footer>
        <div class="pull-right">
          Mata Kuliah Data Warehouse - WHSAKILA</a>
        </div>
        <div class="clearfix"></div>
      </footer>
      <!-- /footer content -->
    </div>
  </div>

  <!-- jQuery -->
  <script src="../vendors/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- FastClick -->
  <script src="../vendors/fastclick/lib/fastclick.js"></script>
  <!-- NProgress -->
  <script src="../vendors/nprogress/nprogress.js"></script>
  <!-- Chart.js -->
  <script src="../vendors/Chart.js/dist/Chart.min.js"></script>
  <!-- gauge.js -->
  <script src="../vendors/gauge.js/dist/gauge.min.js"></script>
  <!-- bootstrap-progressbar -->
  <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
  <!-- iCheck -->
  <script src="../vendors/iCheck/icheck.min.js"></script>
  <!-- Skycons -->
  <script src="../vendors/skycons/skycons.js"></script>
  <!-- Flot -->
  <script src="../vendors/Flot/jquery.flot.js"></script>
  <script src="../vendors/Flot/jquery.flot.pie.js"></script>
  <script src="../vendors/Flot/jquery.flot.time.js"></script>
  <script src="../vendors/Flot/jquery.flot.stack.js"></script>
  <script src="../vendors/Flot/jquery.flot.resize.js"></script>
  <!-- Flot plugins -->
  <script src="../vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
  <script src="../vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
  <script src="../vendors/flot.curvedlines/curvedLines.js"></script>
  <!-- DateJS -->
  <script src="../vendors/DateJS/build/date.js"></script>
  <!-- JQVMap -->
  <script src="../vendors/jqvmap/dist/jquery.vmap.js"></script>
  <script src="../vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
  <script src="../vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
  <!-- bootstrap-daterangepicker -->
  <script src="../vendors/moment/min/moment.min.js"></script>
  <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

  <!-- Custom Theme Scripts -->
  <script src="../build/js/custom.min.js"></script>

</body>

</html>