<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'company' => $this->company,
            'company_logo' => $this->company_logo,
            'location' => $this->location,
            'category' => $this->category,
            'salary' => $this->salary,
            'description' => $this->description,
            'benefits' => $this->benefits,
            'type' => $this->type,
            'work_condition' => $this->work_condition,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->created_at,
        ];
    }
}
