<?php

namespace Modules\Auth\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Models\User;

/**
 * @OA\Schema(
 *     schema="LoginUserResource",
 *     type="object",
 *
 *     @OA\Property(
 *         property="user",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="email", type="string", format="email", example="john@example.com")
 *     ),
 *     @OA\Property(
 *         property="token",
 *         type="string",
 *         example="some-jwt-token"
 *     )
 * )
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $token
 */
class LoginUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
            ],
            'token' => 'Bearer '.$this->token,
            'token_type' => 'Bearer',
        ];
    }
}
