<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbDatabase = "whsakila2021";

$mysqli = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbDatabase);
//QUERY CHART PERTAMA

//query untuk tahu SUM(Amount) semuanya
$sql = "SELECT sum(amount) as tot from fakta_pendapatan";
$tot = mysqli_query($mysqli, $sql);
$tot_amount = mysqli_fetch_row($tot);

// echo $tot_amount[0];

//query untuk ambil penjualan berdasarkan kategori, query sudah dimodifikasi
//ditambahkan label variabel DATA.

$sql = "SELECT concat('name:',s.nama_kota) as name, concat('y:', sum(fp.amount)*100/'" . $tot_amount[0] . "') as y, concat('drilldown:', s.nama_kota) as drilldown
			FROM store s
			JOIN fakta_pendapatan fp ON (s.store_id = fp.store_id)
			GROUP BY name
			ORDER BY y DESC";
//echo $sql;
$all_kat = mysqli_query($mysqli, $sql);

while ($row = mysqli_fetch_all($all_kat)) {
    $data[] = $row;
}


$json_all_kat = json_encode($data);
// print_r($json_all_kat);
//CHART KEDUA (DRILL DOWN)

//query untuk tahu SUM(Amount) semua kategori
$sql = "SELECT s.nama_kota nama_kota, sum(fp.amount) as tot_kat
			FROM fakta_pendapatan fp
			JOIN store s ON (s.store_id = fp.store_id)
			GROUP BY nama_kota";
$hasil_kat = mysqli_query($mysqli, $sql);

while ($row = mysqli_fetch_all($hasil_kat)) {
    $tot_all_kat[] = $row;
}

// print_r($tot_all_kat);
//function untuk nyari total_per_kat

//echo count($tot_per_kat[0]);
//echo $tot_per_kat[0][0][1];

function cari_tot_kat($kat_dicari, $tot_all_kat)
{
    $counter = 0;
    // echo $tot_all_kat[0];
    while ($counter < count($tot_all_kat[0])) {
        if ($kat_dicari == $tot_all_kat[0][$counter][0]) {
            $tot_kat = $tot_all_kat[0][$counter][1];
            return $tot_kat;
        }
        $counter++;
    }
}

//query untuk ambil penjualan di kategori berdasarkan bulan (clean)
$sql = "SELECT s.nama_kota nama_kota,
			t.namahari as namahari,
			sum(fp.amount) as pendapatan
			FROM store s
			JOIN fakta_pendapatan fp ON (s.store_id = fp.store_id)
			JOIN time t on (t.time_id = fp.time_id)
			GROUP BY nama_kota, namahari";
$det_kat = mysqli_query($mysqli, $sql);
$i = 0;
while ($row = mysqli_fetch_all($det_kat)) {
    //echo $row;
    $data_det[] = $row;
}

//print_r($data_det);

//PERSIAPAN DATA DRILL DOWN - TEKNIK CLEAN
$i = 0;

//inisiasi string DATA
$string_data = "";
$string_data .= '{name:"' . $data_det[0][$i][0] . '", id:"' . $data_det[0][$i][0] . '", data: [';


// echo cari_tot_kat("Action", $tot_all_kat);
foreach ($data_det[0] as $a) {
    //echo cari_tot_kat($a[0], $tot_all_kat);

    if ($i < count($data_det[0]) - 1) {
        if ($a[0] != $data_det[0][$i + 1][0]) {
            $string_data .= '["' . $a[1] . '", ' .
                $a[2] * 100 / cari_tot_kat($a[0], $tot_all_kat) . ']]},';
            $string_data .= '{name:"' . $data_det[0][$i + 1][0] . '", id:"' . $data_det[0][$i + 1][0] . '", data: [';
        } else {
            $string_data .= '["' . $a[1] . '", ' .
                $a[2] * 100 / cari_tot_kat($a[0], $tot_all_kat) . '], ';
        }
    } else {

        $string_data .= '["' . $a[1] . '", ' .
            $a[2] * 100 / cari_tot_kat($a[0], $tot_all_kat) . ']]}';
    }


    $i = $i + 1;
}
