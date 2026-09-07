<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\User;
use App\Models\Institution;
use App\Mail\RoomBookingMail;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RoomBookingFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $institution;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->seed(RolePermissionSeeder::class);

        // Create a default institution for users and rooms
        $this->institution = Institution::create([
            'name' => 'G-Roster Test Corp',
            'address' => 'Test Street 123',
            'contact_person' => 'John Doe',
            'contact_phone' => '1234567890',
        ]);
    }

    public function test_pic_can_request_room_booking()
    {
        Mail::fake();

        // Create a PIC user
        $pic = User::factory()->create([
            'institution_id' => $this->institution->id,
        ]);
        $pic->assignRole('pic');

        // Create a Room
        $room = Room::create([
            'name' => 'Ruang Rapat Utama',
            'capacity' => 10,
            'status' => 'available',
            'institution_id' => $this->institution->id,
        ]);

        // Act
        $response = $this->actingAs($pic)->post(route('roombookings.store'), [
            'room_id' => $room->id,
            'date' => '2026-06-01',
            'start_time' => '09:00',
            'end_time' => '10:00',
        ]);

        // Assert
        $response->assertRedirect(route('roombookings.index'));
        $this->assertDatabaseHas('room_bookings', [
            'room_id' => $room->id,
            'user_id' => $pic->id,
            'is_active' => 0, // Auto-inactive for PIC requests
        ]);

        // Assert mail was sent to PIC and Admins
        Mail::assertSent(RoomBookingMail::class, function ($mail) use ($pic) {
            return $mail->type === 'requested_pic' && $mail->hasTo($pic->email);
        });
    }

    public function test_admin_can_approve_room_booking()
    {
        Mail::fake();

        // Create Admin & PIC user
        $admin = User::factory()->create([
            'institution_id' => $this->institution->id,
        ]);
        $admin->assignRole('admin');

        $pic = User::factory()->create([
            'institution_id' => $this->institution->id,
        ]);
        $pic->assignRole('pic');

        // Create a Room
        $room = Room::create([
            'name' => 'Ruang Rapat Utama',
            'capacity' => 10,
            'status' => 'available',
            'institution_id' => $this->institution->id,
        ]);

        // Create a pending booking
        $booking = RoomBooking::create([
            'room_id' => $room->id,
            'user_id' => $pic->id,
            'date' => '2026-06-01 00:00:00',
            'start_time' => '2026-06-01 09:00:00',
            'end_time' => '2026-06-01 10:00:00',
            'is_active' => 0,
        ]);

        // Act
        $response = $this->actingAs($admin)->post(route('roombookings.approve', $booking->id));

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('room_bookings', [
            'id' => $booking->id,
            'is_active' => 1,
        ]);

        // Room status should update to booked
        $room->refresh();
        $this->assertEquals('booked', $room->status);

        // Assert mail was sent to PIC
        Mail::assertSent(RoomBookingMail::class, function ($mail) use ($pic) {
            return $mail->type === 'approved' && $mail->hasTo($pic->email);
        });
    }

    public function test_admin_can_reject_room_booking()
    {
        Mail::fake();

        // Create Admin & PIC user
        $admin = User::factory()->create([
            'institution_id' => $this->institution->id,
        ]);
        $admin->assignRole('admin');

        $pic = User::factory()->create([
            'institution_id' => $this->institution->id,
        ]);
        $pic->assignRole('pic');

        // Create a Room
        $room = Room::create([
            'name' => 'Ruang Rapat Utama',
            'capacity' => 10,
            'status' => 'available',
            'institution_id' => $this->institution->id,
        ]);

        // Create a pending booking
        $booking = RoomBooking::create([
            'room_id' => $room->id,
            'user_id' => $pic->id,
            'date' => '2026-06-01 00:00:00',
            'start_time' => '2026-06-01 09:00:00',
            'end_time' => '2026-06-01 10:00:00',
            'is_active' => 0,
        ]);

        // Act
        $response = $this->actingAs($admin)->post(route('roombookings.reject', $booking->id));

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('room_bookings', [
            'id' => $booking->id,
            'is_active' => 0,
        ]);

        // Room status should remain/be available
        $room->refresh();
        $this->assertEquals('available', $room->status);

        // Assert mail was sent to PIC
        Mail::assertSent(RoomBookingMail::class, function ($mail) use ($pic) {
            return $mail->type === 'rejected' && $mail->hasTo($pic->email);
        });
    }
}
