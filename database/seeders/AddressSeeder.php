<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Create 1–3 addresses per user
            $addresses = Address::factory()
                ->count(rand(1, 3))
                ->make(); // make() instead of create()

            $isFirst = true;
            foreach ($addresses as $address) {
                $address->user_id = $user->id;
                $address->is_default = $isFirst ? 1 : 0;
                $address->save();

                $isFirst = false; // Only the first one will be default
            }
        }
    }
}
