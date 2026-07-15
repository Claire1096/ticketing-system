<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 1. Generate a realistic creation timestamp from the last 7 days (Asia/Manila)
        $createdAt = fake()->dateTimeBetween('-7 days', 'now', 'Asia/Manila');

        // 2. Randomly assign a ticket status matching your exact Enum values
        $status = fake()->randomElement(['Open', 'In Progress', 'Pending', 'Resolved', 'Closed']);

        // 3. If resolved or closed, set a resolution time after its creation; otherwise, leave it null
        $resolvedAt = in_array($status, ['Resolved', 'Closed']) 
            ? fake()->dateTimeBetween($createdAt, 'now', 'Asia/Manila') 
            : null;

        return [
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement(['Hardware', 'Software', 'Network', 'Printer', 'Internet', 'Others']),
            'department' => fake()->randomElement(['HR', 'Finance', 'Marketing', 'Sales', 'Operations']),
            'priority' => fake()->randomElement(['Low', 'Medium', 'High', 'Critical']),
            'status' => $status,
            'submitted_by' => 1, // Assumes user ID 1 exists in your users table.
            'assigned_to' => fake()->optional(0.7, null)->randomElement([1]), // 70% chance of being assigned to User 1
            'first_response_at' => fake()->optional(0.5)->dateTimeBetween($createdAt, $resolvedAt ?? 'now', 'Asia/Manila'),
            'technician_remarks' => $resolvedAt ? fake()->sentence() : null,
            'resolved_at' => $resolvedAt,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}