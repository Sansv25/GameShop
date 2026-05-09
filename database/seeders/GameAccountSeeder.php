<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GameAccount;

class GameAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'title' => 'Mobile Legends Account Level 50',
                'description' => 'High level account with rare skins and heroes',
                'category' => 'Mobile Legends',
                'price' => 50000,
                'username' => 'ml_player_001',
                'password' => 'securepass123',
                'status' => 'available',
            ],
            [
                'title' => 'Free Fire Account with 1000 Diamonds',
                'description' => 'Premium account with lots of diamonds and skins',
                'category' => 'Free Fire',
                'price' => 75000,
                'username' => 'ff_gamer_002',
                'password' => 'diamondpass456',
                'status' => 'available',
            ],
            [
                'title' => 'PUBG Mobile Pro Account',
                'description' => 'Professional account with UC and rare items',
                'category' => 'PUBG Mobile',
                'price' => 100000,
                'username' => 'pubg_pro_003',
                'password' => 'ucpass789',
                'status' => 'available',
            ],
            [
                'title' => 'Genshin Impact Account AR 60',
                'description' => 'High adventure rank with multiple 5-star characters',
                'category' => 'Genshin Impact',
                'price' => 150000,
                'username' => 'genshin_master_004',
                'password' => 'adventure123',
                'status' => 'available',
            ],
            [
                'title' => 'Valorant Account with Vandal',
                'description' => 'Competitive account with premium skins',
                'category' => 'Valorant',
                'price' => 200000,
                'username' => 'valorant_pro_005',
                'password' => 'vandalpass',
                'status' => 'available',
            ],
            [
                'title' => 'COD Mobile Max Level',
                'description' => 'Maximum level account with all unlocks',
                'category' => 'COD Mobile',
                'price' => 80000,
                'username' => 'cod_max_006',
                'password' => 'codsecure789',
                'status' => 'available',
            ],
            [
                'title' => 'Among Us Crewmate Account',
                'description' => 'Fun account for social gaming',
                'category' => 'Among Us',
                'price' => 25000,
                'username' => 'amongus_fun_007',
                'password' => 'crewmate123',
                'status' => 'available',
            ],
            [
                'title' => 'Roblox Builder Account',
                'description' => 'Premium account with building tools',
                'category' => 'Roblox',
                'price' => 60000,
                'username' => 'roblox_builder_008',
                'password' => 'buildpass456',
                'status' => 'available',
            ],
            [
                'title' => 'Minecraft Java Edition',
                'description' => 'Full access account with premium features',
                'category' => 'Minecraft',
                'price' => 120000,
                'username' => 'minecraft_pro_009',
                'password' => 'blockpass789',
                'status' => 'available',
            ],
            [
                'title' => 'Fortnite Battle Pass Account',
                'description' => 'Account with active battle pass and V-Bucks',
                'category' => 'Fortnite',
                'price' => 90000,
                'username' => 'fortnite_vbucks_010',
                'password' => 'battlepass123',
                'status' => 'available',
            ],
        ];

        foreach ($accounts as $account) {
            GameAccount::create($account);
        }
    }
}
