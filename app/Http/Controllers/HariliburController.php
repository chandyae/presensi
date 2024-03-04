<?php

namespace App\Http\Controllers;

use App\Models\Harilibur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class HariliburController extends Controller
{
    public function index ()
    {
        $query = Harilibur::query();
        $query->orderBy('kode_libur','desc');
        $harilibur = $query->paginate(10);
        return view ('harilibur.index', compact('harilibur'));
    }
    
    public function create(){
        return view('harilibur.create');
    }

    public function store(Request $request)
    {
        $tahun = date('Y', strtotime($request->tanggal_libur));
        $thn = substr($tahun, 2, 2);
        $lastlibur = DB::table('harilibur')
            ->whereRaw('YEAR(tanggal_libur)="' . $tahun . '"')
            ->orderBy('kode_libur', 'desc')
            ->first();
        $lastkodelibur = $lastlibur != null ? $lastlibur->kode_libur : "";
        $format = "LB" . $thn;
        $kode_libur = buatkode($lastkodelibur, $format, 2);
 
        try {
            DB::table('harilibur')
            ->insert([
                'kode_libur' => $kode_libur,
                'tanggal_libur' => $request->tanggal_libur,
                'keterangan' =>$request->keterangan
            ]);

            return Redirect::back()->with(['success' => 'Data berhasil disimpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => $e->getMessage()]);
        }
    }
    
    public function edit(Request $request){
        $kode_libur = $request->kode_libur;
        $harilibur = DB::table('harilibur')->where('kode_libur',$kode_libur)->first();
        return view ('harilibur.edit', compact('harilibur'));
    }

    public function update(Request $request, $kode_libur){ 
       
        try {
            DB::table('harilibur')
            ->where('kode_libur',$kode_libur)
            ->update([
                'tanggal_libur' => $request->tanggal_libur,
                'keterangan' =>$request->keterangan
            ]);

            return Redirect::back()->with(['success' => 'Data berhasil diupdate']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => $e->getMessage()]);
        }
    }

    public function delete($kode_libur)
    {
        try {
            DB::table('harilibur')
            ->where('kode_libur',$kode_libur)->delete();
            return Redirect::back()->with(['success' => 'Data berhasil di hapus']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => $e->getMessage()]);
        }
    }    
}