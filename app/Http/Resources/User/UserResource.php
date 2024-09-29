<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'position' => $this->position->name,
            'position_id' => $this->position_id,
            'photo' => $this->getPhotoUrl(),
        ];
    }

    protected function getPhotoUrl()
    {
        if (strpos($this->photo, 'http') === 0) {
            return $this->photo;
        }

        return config('app.url') . Storage::url($this->photo);
    }
}
