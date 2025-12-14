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

            if ($start && $end && $start >= $end) {
                $validator->errors()->add('start_time', '出勤時間もしくは退勤時間が不適切な値です');
            }

            foreach ($breaks as $i => $break) {
                    $b_start = $break['start_time'] ?? null;
                    $b_end = $break['end_time'] ?? null;

                    if ($b_start) {
                        if ($b_start < $start || $b_start > $end) {
                            $validator->errors()->add("breaks.$i.start_time", '休憩時間が不適切な値です');
                        }
                    }

                    if ($b_end) {
                        if ($b_end > $end ) {
                            $validator->errors()->add("breaks.$i.end_time", '休憩時間もしくは退勤時間が不適切な値です');
                        }

                        if ($b_start && $b_end < $b_start) {
                            $validator->errors()->add("breaks.$i.end_time", '休憩時間が不適切な値です');
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
