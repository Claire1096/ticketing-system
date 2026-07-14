<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        \App\Models\Faq::insert([
    [
        'question' => 'My label printer keeps jamming mid-batch',
        'answer' => 'Open the rear panel and clear any adhesive residue from the roller before restarting. If the label stock was changed recently, confirm the printer profile matches the new size in Settings. Still stuck after that? File a Printer ticket.',
        'category' => 'Printer',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'question' => 'I can\'t log into the inventory system',
        'answer' => 'Passwords expire every 90 days. Try resetting via the link on the login page first. If your account shows as locked, that needs an IT reset — open a Software ticket.',
        'category' => 'Software',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'question' => 'Wi-Fi is slow in certain areas of the building',
        'answer' => 'Some rooms have known interference with the nearest access point. Try switching to a different available network manually while a permanent fix is scoped.',
        'category' => 'Network',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'question' => 'How long does a ticket usually take to resolve?',
        'answer' => 'Response times depend on priority: Critical within 1 hour, High within 4 hours, Medium within 1 business day, Low within 3 business days.',
        'category' => 'Others',
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);
    }
}
