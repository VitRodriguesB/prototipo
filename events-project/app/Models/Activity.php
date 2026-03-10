<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // 👈 Importe

class Activity extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'event_id',
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
        'max_participants',
    ];

    /**
     * Os atributos que devem ser convertidos (casts).
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    /**
     * Define o relacionamento: Uma atividade pertence a um Evento.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Participantes inscritos nesta atividade (RF_F9).
     */
    public function participants(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'activity_user');
    }
}