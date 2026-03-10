<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'event_date',
        'registration_deadline',
        'registration_fee',
        'max_participants',
        'pix_key',
        'cover_image_path', // 👈 ADICIONADO
    ];

    /**
     * Converte os campos de data para Carbon.
     */
    protected $casts = [
        'event_date' => 'datetime',
        'registration_deadline' => 'datetime',
    ];

    /**
     * Um evento tem muitos tipos de inscrição.
     */
    public function inscriptionTypes(): HasMany
    {
        return $this->hasMany(InscriptionType::class);
    }
    
    /**
     * Um evento tem muitas atividades (palestras, oficinas, etc).
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Um evento pertence a um Organizador (User).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}