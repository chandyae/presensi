<?php
function hitungjamterlambat($jadwal_jam_masuk, $jam_presensi)
{
    $j1 = strtotime($jadwal_jam_masuk);
    $j2 = strtotime($jam_presensi);

    $diffterlambat = $j2 - $j1;

    $jamterlambat = floor($diffterlambat / (60 * 60));
    $menitterlambat = floor(($diffterlambat - ($jamterlambat * (60 * 60))) / 60);

    $jterlambat = $jamterlambat <= 9 ? "0" . $jamterlambat : $jamterlambat;
    $mterlambat = $menitterlambat <= 9 ? "0" . $menitterlambat : $menitterlambat;


    $terlambat = $jterlambat . ":" . $mterlambat;
    return $terlambat;
}


function hitungjamterlambatdesimal($jam_masuk, $jam_presensi)
{
    $j1 = strtotime($jam_masuk);
    $j2 = strtotime($jam_presensi);

    $diffterlambat = $j2 - $j1;

    $jamterlambat = floor($diffterlambat / (60 * 60));
    $menitterlambat = floor(($diffterlambat - ($jamterlambat * (60 * 60))) / 60);

    $jterlambat = $jamterlambat <= 9 ? "0" . $jamterlambat : $jamterlambat;
    $mterlambat = $menitterlambat <= 9 ? "0" . $menitterlambat : $menitterlambat;


    $desimalterlambat = $jamterlambat + ROUND(($menitterlambat / 60), 2);
    return  $desimalterlambat;
}



function hitunghari($tanggal_mulai, $tanggal_akhir)
{
    $tanggal_1 = date_create($tanggal_mulai);
    $tanggal_2 = date_create($tanggal_akhir); // waktu sekarang
    $diff = date_diff($tanggal_1, $tanggal_2);
    return $diff->days + 1;
}



function buatkode($nomor_terakhir, $kunci, $jumlah_karakter = 0)
{
    /* mencari nomor baru dengan memecah nomor terakhir dan menambahkan 1
    string nomor baru dibawah ini harus dengan format XXX000000
    untuk penggunaan dalam format lain anda harus menyesuaikan sendiri */
    $nomor_baru = intval(substr($nomor_terakhir, strlen($kunci))) + 1;
    //    menambahkan nol didepan nomor baru sesuai panjang jumlah karakter
    $nomor_baru_plus_nol = str_pad($nomor_baru, $jumlah_karakter, "0", STR_PAD_LEFT);
    //    menyusun kunci dan nomor baru
    $kode = $kunci . $nomor_baru_plus_nol;
    return $kode;
}


function hitungjamkerja($tgl_presensi, $jam_mulai, $jam_pulang, $max_total_jam,$lintashari)
{
    if($lintashari=='1'){
        $tanggal_pulang = date ('Y-m-d', strtotime("+1 days", strtotime($tgl_presensi))); 
    }else{
        $tanggal_pulang = $tgl_presensi;
    }
    $jam_mulai = $tgl_presensi . " " . $jam_mulai;
    $jam_pulang = $tanggal_pulang . " " . $jam_pulang;
    $j_mulai = strtotime($jam_mulai);
    $j_pulang = strtotime($jam_pulang);
    $diff = $j_pulang - $j_mulai;
    if (empty($j_pulang)) {
        $jam = 0;
        $menit = 0;
    } else {
        $jam = floor($diff / (60 * 60));
        $m = $diff - $jam * (60 * 60);
        $menit = floor($m / 60);
    }
    
    $menitdesimal = ROUND($menit / 60, 2);
    $jamdesimal = $jam + $menitdesimal;
    $totaljam = $jamdesimal > $max_total_jam ? $max_total_jam : $jamdesimal;
    // return $jam . ":" . $menit." (". $totaljam .")";
    return $totaljam;
}

function gethari($hari)
    {
        // $hari = date("D");

        switch ($hari) {
            case 'Sun':
                $hari_ini = "Minggu";
                break;

            case 'Mon':
                $hari_ini = "Senin";
                break;

            case 'Tue':
                $hari_ini = "Selasa";
                break;

            case 'Wed':
                $hari_ini = "Rabu";
                break;

            case 'Thu':
                $hari_ini = "Kamis";
                break;

            case 'Fri':
                $hari_ini = "Jumat";
                break;

            case 'Sat':
                $hari_ini = "Sabtu";
                break;

            default:
                $hari_ini = "Tidak di ketahui";
                break;
        }

        return $hari_ini;
    }
