<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceChangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'note' => 'required',
            'breaks.*.start_time' => 'nullable|date_format:H:i',
            'breaks.*.end_time' => 'nullable|date_format:H:i',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $start = $this->input('start_time');
            $end = $this->input('end_time');
            $breaks = $this->input('breaks', []);

            if ($start && $end) {
                if ($start >= $end) {
                    $validator->errors()->add(
                        'start_time',
                        '出勤時間が不適切な値です'
                    );

                    $validator->errors()->add(
                        'end_time',
                        '退勤時間が不適切な値です'
                    );
                }
            }

            foreach ($breaks as $breakIndex => $break) {
                    $breakStartTime = $break['start_time'] ?? null;
                    $breakEndTime = $break['end_time'] ?? null;

                    if ($breakStartTime) {
                        if ($breakStartTime < $startTime || $breakStartTime > $endTime) {
                            $validator->errors()->add("breaks.$breakIndex.start_time", '休憩時間が不適切な値です');
                        }
                    }

                    if ($breakEndTime) {
                        if ($breakEndTime > $endTime ) {
                            $validator->errors()->add("breaks.$breakIndex.end_time", '休憩時間もしくは退勤時間が不適切な値です');
                        }

                        if ($breakStartTime && $breakEndTime < $breakStartTime) {
                            $validator->errors()->add("breaks.$breakIndex.end_time", '休憩時間が不適切な値です');
                        }
                    }
            }
        });
    }

    public function messages()
    {
        return [
            'note.required' => '備考を記入してください',
        ];
    }
}
