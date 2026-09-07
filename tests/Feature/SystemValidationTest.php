<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Institution;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\User;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomBookingController;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SystemValidationTest extends TestCase
{
    use RefreshDatabase;

    protected Institution $institution;
    protected User $pic;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'pic']);
        Role::firstOrCreate(['name' => 'participant']);

        $this->institution = Institution::create([
            'name' => 'Institusi Uji',
            'address' => 'Jl. Uji No.1',
            'contact_person' => 'Anifa',
            'contact_phone' => '08123456789',
        ]);

        $this->pic = User::create([
            'name' => 'PIC Uji',
            'email' => 'pic@test.local',
            'password' => bcrypt('password'),
            'institution_id' => $this->institution->id,
        ]);
        $this->pic->assignRole('pic');
    }

    protected function makeRoom(string $name, int $capacity, string $status = 'available'): Room
    {
        return Room::create([
            'name' => $name,
            'institution_id' => $this->institution->id,
            'capacity' => $capacity,
            'status' => $status,
        ]);
    }

    protected function makeEvent(): Event
    {
        return Event::create([
            'name' => 'Acara Uji',
            'user_id' => $this->pic->id,
            'start_date' => now(),
            'end_date' => now()->addHour(),
            'is_approved' => 1,
        ]);
    }

    /** TC1: Kapasitas & waktu cukup -> ruangan direkomendasikan */
    public function test_tc1_kapasitas_dan_waktu_cukup_ruangan_tersedia()
    {
        $this->makeRoom('Ruang A', 50);

        $request = Request::create('/roombookings/recommend', 'GET', [
            'date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'capacity' => 30,
        ]);

        $response = (new RoomBookingController())->recommendRooms($request);
        $data = $response->getData(true);

        $this->assertCount(1, $data['rooms']);
        $this->assertNull($data['suggestion']);
    }

    /** TC2: Kapasitas diminta melebihi semua ruangan -> suggestion fits_capacity = false */
    public function test_tc2_kapasitas_melebihi_semua_ruangan()
    {
        $this->makeRoom('Ruang A', 10);

        $request = Request::create('/roombookings/recommend', 'GET', [
            'date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'capacity' => 50,
        ]);

        $response = (new RoomBookingController())->recommendRooms($request);
        $data = $response->getData(true);

        $this->assertCount(0, $data['rooms']);
        $this->assertFalse($data['suggestion']['fits_capacity']);
        $this->assertEquals(10, $data['suggestion']['max_capacity']);
    }

    /** TC3: Kapasitas cukup tapi ruangan sedang dipakai pada jam yang diminta -> next_available_time terisi */
    public function test_tc3_kapasitas_cukup_tapi_waktu_bentrok()
    {
        $room = $this->makeRoom('Ruang A', 30);
        $event = $this->makeEvent();
        $date = now()->addDay()->format('Y-m-d');

        RoomBooking::create([
            'room_id' => $room->id,
            'user_id' => $this->pic->id,
            'event_id' => $event->id,
            'date' => $date . ' 00:00:00',
            'start_time' => $date . ' 09:00:00',
            'end_time' => $date . ' 11:00:00',
            'is_active' => 1,
        ]);

        $request = Request::create('/roombookings/recommend', 'GET', [
            'date' => $date,
            'start_time' => '09:30',
            'end_time' => '10:30',
            'capacity' => 20,
        ]);

        $response = (new RoomBookingController())->recommendRooms($request);
        $data = $response->getData(true);

        $this->assertCount(0, $data['rooms']);
        $this->assertTrue($data['suggestion']['fits_capacity']);
        $this->assertEquals('11:00', $data['suggestion']['next_available_time']);
    }

    /** TC4: Boundary - waktu baru tepat mulai saat booking lain berakhir (tidak overlap) -> tersedia */
    public function test_tc4_boundary_waktu_persis_setelah_booking_selesai()
    {
        $room = $this->makeRoom('Ruang A', 30);
        $event = $this->makeEvent();
        $date = now()->addDay()->format('Y-m-d');

        RoomBooking::create([
            'room_id' => $room->id,
            'user_id' => $this->pic->id,
            'event_id' => $event->id,
            'date' => $date . ' 00:00:00',
            'start_time' => $date . ' 09:00:00',
            'end_time' => $date . ' 10:00:00',
            'is_active' => 1,
        ]);

        $request = Request::create('/roombookings/recommend', 'GET', [
            'date' => $date,
            'start_time' => '10:00',
            'end_time' => '11:00',
            'capacity' => 20,
        ]);

        $response = (new RoomBookingController())->recommendRooms($request);
        $data = $response->getData(true);

        $this->assertCount(1, $data['rooms']);
    }

    /** TC5: Mulai sesi sebelum jadwal -> ditolak */
    public function test_tc5_mulai_sesi_sebelum_jadwal_ditolak()
    {
        $room = $this->makeRoom('Ruang A', 30);
        $event = $this->makeEvent();

        $booking = RoomBooking::create([
            'room_id' => $room->id,
            'user_id' => $this->pic->id,
            'event_id' => $event->id,
            'date' => now()->format('Y-m-d') . ' 00:00:00',
            'start_time' => now()->addMinutes(30),
            'end_time' => now()->addHour(),
            'is_active' => 1,
        ]);

        $response = (new RoomBookingController())->startSession($booking);

        $this->assertEquals('available', $room->fresh()->status);
        $this->assertNotNull($response->getSession()->get('error'));
    }

    /** TC6: Boundary - mulai sesi tepat di start_time -> diterima */
    public function test_tc6_boundary_mulai_sesi_tepat_di_start_time()
    {
        $room = $this->makeRoom('Ruang A', 30);
        $event = $this->makeEvent();

        $booking = RoomBooking::create([
            'room_id' => $room->id,
            'user_id' => $this->pic->id,
            'event_id' => $event->id,
            'date' => now()->format('Y-m-d') . ' 00:00:00',
            'start_time' => now(),
            'end_time' => now()->addHour(),
            'is_active' => 1,
        ]);

        $response = (new RoomBookingController())->startSession($booking);

        $this->assertEquals('occupied', $room->fresh()->status);
        $this->assertNotNull($response->getSession()->get('success'));
    }

    /** TC7: Mulai sesi setelah jadwal berakhir -> ditolak */
    public function test_tc7_mulai_sesi_setelah_jadwal_berakhir_ditolak()
    {
        $room = $this->makeRoom('Ruang A', 30);
        $event = $this->makeEvent();

        $booking = RoomBooking::create([
            'room_id' => $room->id,
            'user_id' => $this->pic->id,
            'event_id' => $event->id,
            'date' => now()->subHour()->format('Y-m-d') . ' 00:00:00',
            'start_time' => now()->subHours(2),
            'end_time' => now()->subHour(),
            'is_active' => 1,
        ]);

        $response = (new RoomBookingController())->startSession($booking);

        $this->assertEquals('available', $room->fresh()->status);
        $this->assertNotNull($response->getSession()->get('error'));
    }

    /** TC8: Presensi tepat saat start_time -> on_time */
    public function test_tc8_presensi_tepat_saat_start_time_on_time()
    {
        [$room, $event, $participant, $booking] = $this->prepareCheckinScenario(now());

        $request = Request::create('/attendances/checkin', 'POST', [
            'user_id' => $participant->id,
            'event_id' => $event->id,
            'room_booking_id' => $booking->id,
        ]);

        $response = (new AttendanceController())->checkin($request);
        $data = $response->getData(true);

        $this->assertEquals('on_time', $data['status']);
    }

    /** TC9: Boundary - presensi tepat 30 menit setelah start_time -> masih on_time */
    public function test_tc9_boundary_presensi_30_menit_masih_on_time()
    {
        $frozenNow = now()->startOfSecond();
        Carbon::setTestNow($frozenNow);

        [$room, $event, $participant, $booking] = $this->prepareCheckinScenario($frozenNow->copy()->subMinutes(30));

        $request = Request::create('/attendances/checkin', 'POST', [
            'user_id' => $participant->id,
            'event_id' => $event->id,
            'room_booking_id' => $booking->id,
        ]);

        $response = (new AttendanceController())->checkin($request);
        $data = $response->getData(true);

        Carbon::setTestNow();

        $this->assertEquals('on_time', $data['status']);
    }

    /** TC10: Presensi 31 menit setelah start_time -> late */
    public function test_tc10_presensi_31_menit_setelah_start_time_late()
    {
        [$room, $event, $participant, $booking] = $this->prepareCheckinScenario(now()->subMinutes(31));

        $request = Request::create('/attendances/checkin', 'POST', [
            'user_id' => $participant->id,
            'event_id' => $event->id,
            'room_booking_id' => $booking->id,
        ]);

        $response = (new AttendanceController())->checkin($request);
        $data = $response->getData(true);

        $this->assertEquals('late', $data['status']);
    }

    /** TC11a: Presensi dua kali pada SESI yang sama -> ditolak (duplikat) */
    public function test_tc11a_presensi_dua_kali_sesi_sama_ditolak()
    {
        [$room, $event, $participant, $booking] = $this->prepareCheckinScenario(now());

        $payload = [
            'user_id' => $participant->id,
            'event_id' => $event->id,
            'room_booking_id' => $booking->id,
        ];

        (new AttendanceController())->checkin(Request::create('/attendances/checkin', 'POST', $payload));
        $second = (new AttendanceController())->checkin(Request::create('/attendances/checkin', 'POST', $payload));

        $this->assertEquals(422, $second->getStatusCode());
        $this->assertEquals(1, \App\Models\Attendance::where('room_booking_id', $booking->id)->count());
    }

    /** TC11b: Bug fix - peserta yang sudah presensi di sesi 1 TETAP BISA presensi lagi
     *  di sesi 2 (room booking baru untuk event yang sama, sesi sebelumnya sudah berakhir). */
    public function test_tc11b_presensi_ulang_diizinkan_untuk_sesi_baru_event_sama()
    {
        [$room, $event, $participant, $bookingSesi1] = $this->prepareCheckinScenario(now()->subHours(3));

        // Sesi 1 sudah lewat, peserta sudah presensi di sesi 1
        $resp1 = (new AttendanceController())->checkin(Request::create('/attendances/checkin', 'POST', [
            'user_id' => $participant->id,
            'event_id' => $event->id,
            'room_booking_id' => $bookingSesi1->id,
        ]));
        $this->assertEquals(200, $resp1->getStatusCode());

        // Pengajuan peminjaman ruangan baru (sesi 2) untuk event yang sama, sedang berlangsung sekarang
        $bookingSesi2 = RoomBooking::create([
            'room_id' => $room->id,
            'user_id' => $this->pic->id,
            'event_id' => $event->id,
            'date' => now()->format('Y-m-d') . ' 00:00:00',
            'start_time' => now()->subMinutes(5),
            'end_time' => now()->addHours(2),
            'is_active' => 1,
        ]);

        $resp2 = (new AttendanceController())->checkin(Request::create('/attendances/checkin', 'POST', [
            'user_id' => $participant->id,
            'event_id' => $event->id,
            'room_booking_id' => $bookingSesi2->id,
        ]));

        $this->assertEquals(200, $resp2->getStatusCode());
        $this->assertEquals(2, \App\Models\Attendance::where('event_id', $event->id)
            ->where('user_id', $participant->id)->count());
    }

    /** TC12: Bug fix - mulai 1 sesi tidak boleh membuat sesi booking LAIN di ruangan
     *  yang sama (tapi jam berbeda, hari ini) ikut tampil sebagai "sedang berlangsung". */
    public function test_tc12_mulai_satu_sesi_tidak_menjalankan_sesi_lain()
    {
        $room = $this->makeRoom('Ruang A', 30);
        $eventA = $this->makeEvent();
        $eventB = $this->makeEvent();

        // Sesi 1: sedang berlangsung sekarang
        $bookingNow = RoomBooking::create([
            'room_id' => $room->id,
            'user_id' => $this->pic->id,
            'event_id' => $eventA->id,
            'date' => now()->format('Y-m-d') . ' 00:00:00',
            'start_time' => now()->subMinutes(5),
            'end_time' => now()->addMinutes(30),
            'is_active' => 1,
        ]);

        // Sesi 2: di ruangan yang sama, hari yang sama, tapi jamnya nanti malam (belum mulai)
        $bookingLater = RoomBooking::create([
            'room_id' => $room->id,
            'user_id' => $this->pic->id,
            'event_id' => $eventB->id,
            'date' => now()->format('Y-m-d') . ' 00:00:00',
            'start_time' => now()->addHours(5),
            'end_time' => now()->addHours(6),
            'is_active' => 1,
        ]);

        (new RoomBookingController())->startSession($bookingNow);

        $this->actingAs($this->pic);
        $view = (new DashboardController())->index();
        $ongoingIds = $view->getData()['ongoing_bookings']->pluck('id')->all();
        $upcomingIds = $view->getData()['upcoming_bookings']->pluck('id')->all();

        $this->assertContains($bookingNow->id, $ongoingIds);
        $this->assertNotContains($bookingLater->id, $ongoingIds);
        $this->assertContains($bookingLater->id, $upcomingIds);
    }

    /**
     * Helper to set up a room+event+room_booking with given scheduled start_time,
     * plus a participant attached to the event. Returns [$room, $event, $participant, $booking].
     */
    protected function prepareCheckinScenario(Carbon $scheduledStart): array
    {
        $room = $this->makeRoom('Ruang A', 30);
        $event = $this->makeEvent();

        $participant = User::create([
            'name' => 'Peserta Uji',
            'email' => 'peserta+' . uniqid() . '@test.local',
            'password' => bcrypt('password'),
            'institution_id' => $this->institution->id,
        ]);
        $participant->assignRole('participant');
        $event->participants()->attach($participant->id);

        $booking = RoomBooking::create([
            'room_id' => $room->id,
            'user_id' => $this->pic->id,
            'event_id' => $event->id,
            'date' => $scheduledStart->format('Y-m-d') . ' 00:00:00',
            'start_time' => $scheduledStart,
            'end_time' => $scheduledStart->copy()->addHours(2),
            'is_active' => 1,
        ]);

        return [$room, $event, $participant, $booking];
    }
}
