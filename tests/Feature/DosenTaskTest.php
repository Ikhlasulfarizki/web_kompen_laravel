<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\Dosen;
use App\Models\User;
use App\Models\Participant;
use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DosenTaskTest extends TestCase
{
    use RefreshDatabase;

    protected $dosen;
    protected $dosenUser;
    protected $mahasiswa;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user with dosen role
        $this->dosenUser = User::factory()->create(['role_id' => 2]);

        // Create dosen record
        $this->dosen = Dosen::create([
            'user_id' => $this->dosenUser->id,
            'nip' => '123456789',
            'nama' => 'Test Dosen',
            'jenis_kelamin' => 'Laki-laki',
            'tgl_lahir' => '1990-01-01',
        ]);

        // Create test mahasiswa for participant testing
        $mahasiswaUser = User::factory()->create(['role_id' => 3]);
        $this->mahasiswa = Mahasiswa::create([
            'user_id' => $mahasiswaUser->id,
            'npm' => '12345678',
            'nama' => 'Test Mahasiswa',
            'jenis_kelamin' => 'Perempuan',
            'tgl_lahir' => '2000-01-01',
            'id_kelas' => 1,
            'jumlah_jam' => 0,
        ]);
    }

    /** @test */
    public function dosen_can_see_task_list()
    {
        $this->actingAs($this->dosenUser)
            ->get(route('dosen.tasks.index'))
            ->assertStatus(200)
            ->assertViewIs('dosen.tasks.index');
    }

    /** @test */
    public function dosen_can_create_task()
    {
        $taskData = [
            'judul' => 'Test Task',
            'deskripsi' => 'This is a test task',
            'lokasi' => 'Room 101',
            'tanggal_waktu' => now()->addDays(1)->format('Y-m-d'),
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'jmlh_jam' => 120, // 2 hours in minutes
            'kuota' => 5,
        ];

        $this->actingAs($this->dosenUser)
            ->post(route('dosen.tasks.store'), $taskData)
            ->assertRedirect(route('dosen.tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'judul' => 'Test Task',
            'id_dosen' => $this->dosen->id,
            'jmlh_jam' => 120,
        ]);
    }

    /** @test */
    public function dosen_can_view_task_detail()
    {
        $task = Task::create([
            'judul' => 'Test Task',
            'lokasi' => 'Room 101',
            'tanggal_waktu' => now()->addDays(1),
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'jmlh_jam' => 120,
            'kuota' => 5,
            'id_dosen' => $this->dosen->id,
        ]);

        $this->actingAs($this->dosenUser)
            ->get(route('dosen.tasks.show', $task->id))
            ->assertStatus(200)
            ->assertViewIs('dosen.tasks.show')
            ->assertSee($task->judul);
    }

    /** @test */
    public function dosen_can_update_task()
    {
        $task = Task::create([
            'judul' => 'Original Task',
            'lokasi' => 'Room 101',
            'tanggal_waktu' => now()->addDays(1),
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'jmlh_jam' => 120,
            'kuota' => 5,
            'id_dosen' => $this->dosen->id,
        ]);

        $updatedData = [
            'judul' => 'Updated Task',
            'deskripsi' => 'Updated description',
            'lokasi' => 'Room 102',
            'tanggal_waktu' => now()->addDays(2)->format('Y-m-d'),
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'jmlh_jam' => 150,
            'kuota' => 10,
        ];

        $this->actingAs($this->dosenUser)
            ->put(route('dosen.tasks.update', $task->id), $updatedData)
            ->assertRedirect(route('dosen.tasks.show', $task->id));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'judul' => 'Updated Task',
            'jmlh_jam' => 150,
        ]);
    }

    /** @test */
    public function dosen_can_delete_task()
    {
        $task = Task::create([
            'judul' => 'Task to Delete',
            'lokasi' => 'Room 101',
            'tanggal_waktu' => now()->addDays(1),
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'jmlh_jam' => 120,
            'kuota' => 5,
            'id_dosen' => $this->dosen->id,
        ]);

        $this->actingAs($this->dosenUser)
            ->delete(route('dosen.tasks.destroy', $task->id))
            ->assertRedirect(route('dosen.tasks.index'));

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    /** @test */
    public function deleting_task_cascades_delete_participants()
    {
        $task = Task::create([
            'judul' => 'Task with Participants',
            'lokasi' => 'Room 101',
            'tanggal_waktu' => now()->addDays(1),
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'jmlh_jam' => 120,
            'kuota' => 5,
            'id_dosen' => $this->dosen->id,
        ]);

        $participant = Participant::create([
            'id_task' => $task->id,
            'id_mhs' => $this->mahasiswa->id,
            'status_pendaftaran' => 'Aktif',
            'status_penyelesaian' => 'Pending',
        ]);

        $this->assertDatabaseHas('participants', [
            'id' => $participant->id,
        ]);

        $this->actingAs($this->dosenUser)
            ->delete(route('dosen.tasks.destroy', $task->id));

        $this->assertDatabaseMissing('participants', [
            'id' => $participant->id,
        ]);
    }

    /** @test */
    public function dosen_can_accept_participant()
    {
        $task = Task::create([
            'judul' => 'Test Task',
            'lokasi' => 'Room 101',
            'tanggal_waktu' => now()->addDays(1),
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'jmlh_jam' => 120,
            'kuota' => 5,
            'id_dosen' => $this->dosen->id,
        ]);

        $participant = Participant::create([
            'id_task' => $task->id,
            'id_mhs' => $this->mahasiswa->id,
            'status_pendaftaran' => 'Aktif',
        ]);

        $this->actingAs($this->dosenUser)
            ->post(route('dosen.participants.accept', $participant->id))
            ->assertRedirect();

        $this->assertDatabaseHas('participants', [
            'id' => $participant->id,
            'status_acc' => 'Diterima',
        ]);
    }

    /** @test */
    public function dosen_can_reject_participant()
    {
        $task = Task::create([
            'judul' => 'Test Task',
            'lokasi' => 'Room 101',
            'tanggal_waktu' => now()->addDays(1),
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'jmlh_jam' => 120,
            'kuota' => 5,
            'id_dosen' => $this->dosen->id,
        ]);

        $participant = Participant::create([
            'id_task' => $task->id,
            'id_mhs' => $this->mahasiswa->id,
            'status_pendaftaran' => 'Aktif',
        ]);

        $this->actingAs($this->dosenUser)
            ->post(route('dosen.participants.reject', $participant->id))
            ->assertRedirect();

        $this->assertDatabaseHas('participants', [
            'id' => $participant->id,
            'status_acc' => 'Ditolak',
        ]);
    }

    /** @test */
    public function marking_participant_as_selesai_increments_mahasiswa_jam()
    {
        $task = Task::create([
            'judul' => 'Test Task',
            'lokasi' => 'Room 101',
            'tanggal_waktu' => now()->addDays(1),
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
            'jmlh_jam' => 120, // 2 hours in minutes
            'kuota' => 5,
            'id_dosen' => $this->dosen->id,
        ]);

        $participant = Participant::create([
            'id_task' => $task->id,
            'id_mhs' => $this->mahasiswa->id,
            'status_pendaftaran' => 'Aktif',
            'status_penyelesaian' => 'Pending',
        ]);

        $this->actingAs($this->dosenUser)
            ->post(route('dosen.participants.update-status', $participant->id), [
                'status_penyelesaian' => 'Selesai',
            ])
            ->assertRedirect();

        $this->mahasiswa->refresh();

        $this->assertEquals(120, $this->mahasiswa->jumlah_jam);
    }

    /** @test */
    public function format_jam_helper_works_correctly()
    {
        $this->assertEquals('1 jam 30 menit', formatJam(90));
        $this->assertEquals('45 menit', formatJam(45));
        $this->assertEquals('2 jam', formatJam(120));
        $this->assertEquals('0 menit', formatJam(0));
    }
}
