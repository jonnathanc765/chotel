<?php

use App\Reservation;
use Illuminate\Database\Seeder;
use App\Room;
use App\Type;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Room::truncate();
        Reservation::truncate();
        Type::truncate();


        $type = factory(Type::class)->create([
            'description' => 'familiar',
            'beds' => 3
        ]);

        factory(Room::class, 15)->create([
            'type_id' => $type->id,
        ]);

        $type = factory(Type::class)->create([
            'description' => 'double',
            'beds' => 2
        ]);

        factory(Room::class, 5)->create([
            'type_id' => $type->id,
        ]);

        $type = factory(Type::class)->create([
            'description' => 'single',
            'beds' => 2
        ]);

        factory(Room::class, 5)->create([
            'type_id' => $type->id,
        ]);

    }
}
