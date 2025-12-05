<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentValidate extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'historia_id'       => 'required',
            'estado_cita_id'    => 'required',
            'user_id'           => 'required',
            'fecha'             => 'required|date|after_or_equal:today', // No permite fechas anteriores a hoyWW
            // Validación de hora: solo rangos 7am-12pm y 3pm-7pm
            'hora' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $hora = Carbon::createFromFormat('H:i', $value);
                    $horaNumero = $hora->hour + ($hora->minute / 60); // Convertir a decimal

                    // Validar rangos permitidos
                    $rangoManana = $horaNumero >= 7 && $horaNumero < 12; // 7:00 AM - 11:59 AM
                    $rangoTarde = $horaNumero >= 15 && $horaNumero < 19; // 3:00 PM - 6:59 PM

                    if (!$rangoManana && !$rangoTarde) {
                        $fail('La hora debe estar entre 7:00 AM - 12:00 PM o 3:00 PM - 7:00 PM.');
                    }
                },
            ],
            'motivo_consulta'   => 'string|nullable',
            'observaciones'     => 'string|nullable',
        ];
    }

    public function messages(): array {
        return [
            'historia_id.required'      => 'La Historia es requerida',
            'estado_cita_id.required'   => 'El Estado de la Cita es requerido',
            'user_id.required'          => 'El Usuario es requerido',
            'fecha.required'            => 'La Fecha es requerida',
            'hora.required'             => 'La Hora es requerida',
            'hora.date_format'          => 'La Hora debe tener el formato HH:mm (ejemplo: 14:30).',
            'hora.after'                => 'La Hora debe ser después de :date.',
            'hora.before'               => 'La Hora debe ser antes de :date.',
            'fecha.date_format'         => 'La Fecha debe tener el formato DD/MM/YYYY (ejemplo: 25/12/2024).',
            'fecha.after_or_equal'      => 'Fecha de la cita debe ser una fecha posterior o igual a hoy.'
        ];
    }

    public function attributes(): array {
        return [
            'hora'          => 'hora del evento',
            'fecha'         => 'fecha del evento',
        ];
    }

    protected function prepareForValidation(): void {
        $this->merge([
            'historia_id'       => trim(strip_tags($this->historia_id)),
            'estado_cita_id'    => trim(strip_tags($this->estado_cita_id)),
            'user_id'           => trim(strip_tags($this->user_id)),
            'fecha'             => trim(strip_tags($this->fecha)),
            'hora'              => trim(strip_tags($this->hora)),
            'motivo_consulta'   => trim(strip_tags($this->motivo_consulta)),
            'observaciones'     => trim(strip_tags($this->observaciones)),
        ]);
    }
}
