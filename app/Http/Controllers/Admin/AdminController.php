<?php
namespace App\Http\Controllers\Admin;

use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function showMahasiswa()
    {
        // INI DIA ELOQUENT YANG DIJANJIKAN, GA PERLU MAKE JOIN JOIN CIK, LGSG MAKE METHOD WITH LANGSUNG KEAMBIL
        // Ambil semua data mahasiswa
        $mahasiswa = Mahasiswa::with('kelas.prodi.jurusan')->get();
        // Ngambil data kelas, prodi, sama jurusan sekaligus gila gak lu
        // Kirim ke view
        return view('admin/dashboard', compact('mahasiswa'));
    }

    public function tambahMahasiswa(){
        $jurusan = Jurusan::all();
        return view('admin/tambah', compact('jurusan'));
    }

    public function storeMahasiswa(Request $request){
        // Validate inputan
        $request->validate( [
            'npm' => 'required|unique:mahasiswa',
            'nama' => 'required',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'id_kelas'    => 'required',
        ]);

        //Nyimpen ke tb-users
        $user = User::create([
            "username" => $request->npm,
            "password" => Hash::make(date("dmY", strtotime($request->tgl_lahir))),
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
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Mahasiswa berhasil ditambahkan!');
    }
    public function editMahasiswa($id){
        $mhs = Mahasiswa::with("kelas.prodi.jurusan")->findOrFail($id);
        $jurusan = Jurusan::all();
        return view('admin/edit', compact('mhs', 'jurusan'));
    }

    public function updateMahasiswa(Request $request, $id){
        $mhs = Mahasiswa::findOrFail($id);
        $mhs->update( [
            'npm' => $request->npm,
            'nama' => $request->nama,
            'tgl_lahir'   => $request->tgl_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_kelas' => $request->id_kelas,
        ]);
        return redirect()->route('admin.dashboard')->with('success','Data Berhasil Diubah!');
    }

    public function deleteMahasiswa($id){
        $mhs = Mahasiswa::findOrFail($id);
        $mhs->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Data berhasil dihapus!');
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
