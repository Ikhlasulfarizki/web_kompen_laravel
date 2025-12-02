<?php
namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    public function showMahasiswa()
    {

        // INI DIA ELOQUENT YANG DIJANJIKAN, GA PERLU MAKE JOIN JOIN CIK, LGSG MAKE METHOD WITH LANGSUNG KEAMBIL
        // Ambil semua data mahasiswa
        $mahasiswa = Mahasiswa::with('kelas.prodi.jurusan')->get();
        // Ngambil data kelas, prodi, sama jurusan sekaligus gila gak lu
        // Kirim ke view
        return view('admin', compact('mahasiswa'));
    }

    public function tambahMahasiswa(){
        $jurusan = jurusan::all();
        return view('tambah', compact('jurusan'));
    }

    public function storeMahasiswa(Request $request){
        // Validate inputan
        $request->validate( [
            'npm'         => 'required|unique:mahasiswa',
            'nama'        => 'required',
            'tgl_lahir'   => 'required|date',
            'jenis_kelamin' => 'required',
            'id_kelas'    => 'required',
        ]);

        $user = User::create([
            "username" => $request->npm,
            "password" => Hash::make($request->tgl_lahir),
            "role_id" => 3,
        ]);

        //Nyimpen ke tb-mahasiswa
        $mahasiswa = Mahasiswa::create([
            "user_id" => $user->id,
            "npm" => $request->npm,
            "tgl_lahir" => $request->tgl_lahir,
            "nama" => $request->nama,
            "jenis_kelamin" => $request->jenis_kelamin,
            "id_kelas" => $request->id_kelas,
            "jumlah_jam" => $request->jumlah_jam,
        ]);
        //Nyimpen ke tb-users

        return redirect()->route('mahasiswa.form')->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    public function getProdi($id_jurusan){
        $prodi = Prodi::where('id_jurusan', $id_jurusan)->get();
        return response()->json($prodi);
    }

    public function getKelas($id_prodi){
        $kelas = Kelas::where('id_prodi', $id_prodi)->get();
        return response()->json($kelas);
    }
}
