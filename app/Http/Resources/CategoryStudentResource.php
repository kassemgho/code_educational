<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryStudentResource extends JsonResource
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
            'student_name' => $this->user->name,
            'attendance_marks' => $this->pivot->attendance_marks,
            'assessment_marks' => $this->pivot->assessment_marks,
            'mark' => $this->exams[0]->pivot->mark,
        ];
    }
}
