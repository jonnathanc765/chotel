<?php

namespace Tests\Feature;

use App\Reservation;
use App\Room;
use App\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationModuleTest extends TestCase
{
    use RefreshDatabase;

    public $room1;
    public $room2;

    public function setup():void
    {
        parent::setUp();

        factory(Type::class)->create([
            'description' => 'familiar',
            'beds' => 3,
        ]);

        factory(Type::class)->create([
            'description' => 'double',
            'beds' => 3,
        ]);

        factory(Type::class)->create([
            'description' => 'single',
            'beds' => 3,
        ]);

        $this->room1 = factory(Room::class)->create([
            'type_id' => 1,
            'price' => 4
        ]);

        $this->room2 = factory(Room::class)->create([
            'type_id' => 2,
            'price' => 10
        ]);

        $this->room2 = factory(Room::class)->create([
            'type_id' => 3,
            'price' => 10
        ]);

    }
    /**
     * @test
     */
    function it_retrieve_rooms_by_type()
    {
        $this->withoutExceptionHandling();

        factory(Room::class, 1)->create([
            'type_id' => 1,
            'price' => 10
        ]);
        factory(Room::class, 2)->create([
            'type_id' => 2,
            'price' => 10
        ]);
        factory(Room::class, 3)->create([
            'type_id' => 3,
            'price' => 10
        ]);

        $from = now();
        $to = now()->addDays(2);

        $this->get("api/room/availability/1/$from/$to")
        ->assertOk()
        ->assertJson([
            'rooms' => [
                'familiar' => 2
            ],
        ])
        ;
    }
    /**
     * @test
     */
    public function user_can_make_reservation()
    {

        $from = now()->addDays(2);
        $to = now()->addDays(3);

        $this->postJson('/api/room/reservation', [
            'checkin' => $from,
            'checkout' => $to,
            'type_id' => 1
        ])
        ->assertCreated();

        $this->assertDatabaseHas('reservations', [
            'check_in' => $from->format('Y-m-d'),
            'check_out' => $to->format('Y-m-d'),
            'room_id' => $this->room1->id
        ]);
    }
    /**
     * @test
     */
    public function user_can_update_a_reservation()
    {
        $this->withoutExceptionHandling();
        $to = now();
        $from = now()->addDay();

        $reservation = factory(Reservation::class)->create([
            'check_in' => $from->format('Y-m-d'),
            'check_out' => $to->format('Y-m-d'),
            'room_id' => $this->room1->id
        ]);

        $from = now()->addDays(2);
        $to = now()->addDays(3);

        $this->putJson('/api/room/reservation/' . $reservation->id, [
            'checkin' => $from,
            'checkout' => $to,
            'type_id' => 1
        ])
        ->assertOk();

        $this->assertDatabaseHas('reservations', [
            'check_in' => $from->format('Y-m-d'),
            'check_out' => $to->format('Y-m-d'),
            'room_id' => $this->room1->id
        ]);
    }
    /**
     * @test
     */
    public function user_can_delete_a_reservation()
    {
        $this->withoutExceptionHandling();
        $to = now();
        $from = now()->addDay();

        $reservation = factory(Reservation::class)->create([
            'check_in' => $from->format('Y-m-d'),
            'check_out' => $to->format('Y-m-d'),
            'room_id' => $this->room1->id
        ]);

        $this->deleteJson('/api/room/reservation/' . $reservation->id)
        ->assertOk()
        ->assertJson([
            'msg' => 'The reservation was deleted successfully.'
        ])
        ;

        $this->assertDatabaseMissing('reservations', [
            'check_in' => $from->format('Y-m-d'),
            'check_out' => $to->format('Y-m-d'),
            'room_id' => $this->room1->id
        ]);
    }
}
