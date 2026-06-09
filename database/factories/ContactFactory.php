<?php

namespace Database\Factories;

use App\Enums\ContactStatus;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'name'        => fake()->name(),
            'email'       => fake()->safeEmail(),
            'phone'       => fake()->phoneNumber(),
            'subject'     => fake()->sentence(4),
            'message'     => fake()->paragraph(),
            'property_id' => null,
            'status'      => ContactStatus::New,
            'admin_notes' => null,
            'replied_at'  => null,
        ];
    }

    public function replied(): static
    {
        return $this->state([
            'status'     => ContactStatus::Replied,
            'replied_at' => now(),
        ]);
    }

    public function read(): static
    {
        return $this->state(['status' => ContactStatus::Read]);
    }
}
