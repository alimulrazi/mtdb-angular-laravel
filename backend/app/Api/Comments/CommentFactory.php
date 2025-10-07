<?php

namespace Api\Comments;

use App\Models\User;
use Arr;
use Api\Auth\Roles\Role;
use Api\Billing\BillingPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content' => $this->faker->realText(),
            'commentable_type' => Arr::random([
                User::class,
                BillingPlan::class,
                Role::class,
            ]),
            'commentable_id' => Arr::random([1, 2, 3, 4, 5]),
            'user_id' => Arr::random([1, 2, 3, 4, 5]),
            'parent_id' => Arr::random([1, null]),
        ];
    }
}

