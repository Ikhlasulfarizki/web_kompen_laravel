<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h2>Daftar Mahasiswa</h2>
<h2><a href="tambah">Tambah Mahasiswa</a></h2>

<table border="1" cellpadding="8">
    <tr>
        <th>NPM</th>
        <th>Nama</th>
        <th>Jenis Kelamin</th>
        <th>Jumlah Jam</th>
        <th>Jurusan</th>
        <th>Prodi</th>
        <th>Kelas</th>
        <th>Action</th>
    </tr>

    @foreach($mahasiswa as $mhs)
        <tr>
            <td>{{ $mhs->npm }}</td>
            <td>{{ $mhs->nama }}</td>
            <td>{{ ($mhs->jenis_kelamin == "L") ? "Laki-Laki" : "Perempuan"}}</td>
            <td>{{ $mhs->jumlah_jam }}</td>
            <td>{{ $mhs->kelas->prodi->jurusan->nama_jurusan }}</td>
            <td>{{ $mhs->kelas->prodi->nama_prodi }}</td>
            <td>{{ $mhs->kelas->nama_kelas }}</td>
            <td>
                <a href="{{route('mahasiswa.edit', $mhs->id)}}">Edit</a>
                <form action="{{ route('mahasiswa.delete', $mhs->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin hapus?')">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>
</body>
</html>
