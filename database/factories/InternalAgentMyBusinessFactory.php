<?php

namespace Database\Factories;

use App\Models\State;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InternalAgentMyBusiness>
 */
class InternalAgentMyBusinessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = new User();
        $agent = $user->with('clients')->has('clients')->inRandomOrder()->first();
        $client = $agent && $agent->clients ? $agent->clients->first() : null;
        $clientStateId = State::inRandomOrder()->first()->id;

        return [
            'agent_id' => $agent->id,
            'agent_full_name' => $agent->first_name . ' ' . $agent->last_name,
            'agent_email' => $agent->email,
            'insurance_company' => $this->faker->company,
            'status' => $this->faker->randomElement(['Submitted', 'Approved', 'Declined', 'Cancelled', 'Withdrawn']),
            'label' => $this->faker->word,
            'product_name' => $this->faker->randomElement(['Whole Life', 'Term Life', 'Final Expense', 'Medicare Supplement', 'Annuity']),
            'application_date' => $this->faker->date(),
            'coverage_amount' => $this->faker->numberBetween(10000, 100000),
            'coverage_length' => $this->faker->randomElement(['5 Years', '10 Years', '15 Years', '20 Years', '25 Years', '30 Years']),
            'premium_frequency' => $this->faker->randomElement(['Monthly', 'Quarterly', 'Semi-Annually', 'Annually']),
            'premium_amount' => $this->faker->numberBetween(100, 1000),
            'premium_volumn' => $this->faker->numberBetween(1, 12),
            'equis_writing_number_carrier' => $this->faker->boolean,
            'carrier_writing_number' => $this->faker->word,
            'this_app_from_lead' => $this->faker->word,
            'source_of_lead' => $this->faker->word,
            'policy_draft_date' => $this->faker->date(),
            'first_name' => $this->faker->firstName,
            'mi' => $this->faker->randomLetter,
            'last_name' => $this->faker->lastName,
            'dob' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'client_street_address_1' => $this->faker->streetAddress,
            'client_street_address_2' => $this->faker->streetAddress,
            'client_city' => $this->faker->city,
            'client_state' => $clientStateId,
            'client_zipcode' => $this->faker->postcode,
            'client_phone_no' => $this->faker->phoneNumber,
            'client_email' => $client->email,
            'created_at' => $this->faker->dateTimeBetween('-60 days', 'now')->format('Y-m-d H:i:s'),
            'updated_at' => $this->faker->dateTimeBetween('-60 days', 'now')->format('Y-m-d H:i:s'),
            'beneficiary_name' => $this->faker->name,
            'beneficiary_relationship' => $this->faker->word,
            'notes' => $this->faker->sentence,
            'client_id' => $client->id,
            'upline_manager' => $this->faker->name,
            'split_sale' => $this->faker->boolean,
            'split_sale_type' => $this->faker->word,
            'split_agent_email' => $this->faker->email,
            'appointment_type' => $this->faker->word,
        ];
    }
}
