<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Ceta Rekap</title>
 
    <style>
        
        #title {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            font-weight: bold;
        }

        .tabeldatakaryawan {
            margin-top: 40px;
        }

        .tabeldatakaryawan tr td {
            padding: 5px;
        }

        .tabelpresensi {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .tabelpresensi tr th {
            border: 1px solid #131212;
            padding: 5px;
            background-color: #dbdbdb;
            font-size: 10px
        }

        .tabelpresensi tr td {
            border: 1px solid #131212;
            padding: 5px;
            font-size: 10px;
        }

        .foto {
            width: 40px;
            height: 30px;

        }

        
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4 landscape">
    
    <section class="sheet padding-10mm">

        <table style="width: 100%">
            <tr>
                <td style="width: 30px">
                    <img src="{{ asset('assets/img/logopresensi.png') }}" width="70" height="70" alt="">
                </td>
                <td>
                    <span id="title">
                    LAPORAN PRESENSI GURU DAN TENAGA PENDIDIK<br>
                        PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }}<br>
                        KECAMATAN PAKISAJI<br>
                    </span>
                    <span><i>Koordinator Wilayah Dinas Pendidikan Kecamatan Pakisaji Kabupaten Malang</i></span>
                
                </td>
            </tr>
        </table>
        <table class="tabelpresensi">
            <tr>
                <!-- <th rowspan="2">NIP</th> -->
                <th rowspan="2">Nama</th>
                <th colspan="{{ $jmlhari }}">Bulan {{ $namabulan[$bulan] }} {{ $tahun }}</th>
                <th rowspan="2">H</th>
                <th rowspan="2">I</th>
                <th rowspan="2">S</th>
                <th rowspan="2">C</th>
                <th rowspan="2">A</th>
            </tr>
            <tr>
                @foreach ($rangetanggal as $d)
                    @if ($d != null)
                        <th>{{ date('d', strtotime($d)) }}</th>
                    @endif
                @endforeach

            </tr>
            @foreach ($rekap as $r)
                <tr>
                    <!-- <td>{{ $r->nik }}</td> -->
                    <td><b>{{ $r->nama_lengkap }}</b><br>
                        NIP. {{$r->nik }} <br>

                    <?php
                    $jml_hadir = 0;
                    $jml_izin = 0;
                    $jml_sakit = 0;
                    $jml_cuti = 0;
                    $jml_alpa = 0;
                    $color = "";
                    for($i=1; $i<=$jmlhari; $i++){
                        $tgl = "tgl_".$i;
                        $tgl_presensi=$rangetanggal[$i-1];
                        $datapresensi = explode("|",$r->$tgl);
                        if($r->$tgl != NULL){
                            $status = $datapresensi[2];
                            $jam_in = $datapresensi[0] !="NA" ? date("H:i",strtotime($datapresensi[0])) :"Belum Absen"; 
                            $jam_out = $datapresensi[1] !="NA" ? date("H:i",strtotime($datapresensi[1])) :"Belum Absen";
                            $jam_masuk = $datapresensi[4] !="NA" ? date("H:i",strtotime($datapresensi[4])) :"";
                            $jam_pulang = $datapresensi[5] !="NA" ? date("H:i",strtotime($datapresensi[5])) :"";
                            $total_jam = $datapresensi[8] !="NA" ? $datapresensi[8] : 0;
                            $lintashari = $datapresensi[9];
                            $jam_mulai = $jam_in > $jam_masuk ? $jam_in : $jam_masuk;
                            if ($jam_in !="NA" && $jam_out != "NA"){
                                $total_jam_kerja = hitungjamkerja($tgl_presensi,$jam_mulai,$jam_out,$total_jam,$lintashari);
                            } else {
                                $total_jam_kerja = 0;
                            }
                        }else{
                            $status = "";
                            $jam_in = "";
                            $jam_out = "";
                            $jam_masuk = "";
                            $jam_pulang = "";
                            $total_jam_kerja = 0;
                        }

                        $cekhari = gethari(date('D',strtotime($tgl_presensi)));

                        if($status == "h"){
                            $jml_hadir += 1;
                            $color = "white";
                        }

                        if($status == "i"){
                            $jml_izin += 1;
                            $color = "#ffbb00";
                        }

                        if($status == "s"){
                            $jml_sakit += 1;
                            $color = "#34a1eb";
                        }

                        if($status == "c"){
                            $jml_cuti += 1;
                            $color = "#a600ff";
                        }


                        if(empty($status) && empty($ceklibur) && $cekhari !='Minggu') {
                            $jml_alpa += 1;
                            $color = "yellow";
                        }

                        if($cekhari == 'Minggu'){
                           $color = "red";
                        }
                ?>
                    <td style="background-color: {{ $color }}">
                      @if ($status == 'h')
                        <!-- <span style="color:green"> -->
                        J:{{$jam_masuk}} - {{$jam_pulang}}
                        <!-- </span> -->
                        <br>
                        A:{{$jam_in}} - {{$jam_out}}
                       
                      <br>
                        
                            Total Jam : {{$total_jam_kerja}}
                           
                       
                      @endif
                    </td>
                    <?php
                    }
                ?>
                    <td>{{ !empty($jml_hadir) ? $jml_hadir : '' }}</td>
                    <td>{{ !empty($jml_izin) ? $jml_izin : '' }}</td>
                    <td>{{ !empty($jml_sakit) ? $jml_sakit : '' }}</td>
                    <td>{{ !empty($jml_cuti) ? $jml_cuti : '' }}</td>
                    <td>{{ !empty($jml_alpa) ? $jml_alpa : '' }}</td>
                </tr>
            @endforeach
        </table>

        <table width="150%" style="margin-top:100px">
        <tr>
                <td style="text-align: center; vertical-align:bottom">
                    Pakisaji, {{ date('d-m-Y') }}<br>
                    Kepala Sekolah
                    <br>
                    <br>
                    <br>
                    <br>
                    
                    <b>___________________________</b><br>
                    NIP. ......................................
                </td>
            </tr>
        </table>
    </section>

</body>

</html>
