<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * Os atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type_id',
    ];

    /**
     * Os atributos que devem estar ocultos.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Os atributos que devem ser convertidos (casts).
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Envia a notificação de verificação de e-mail customizada (RF_B1).
     * Sobrescreve o método padrão do Laravel para usar nossa notificação em português.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new CustomVerifyEmail());
    }

    /**
     * Verifica se o usuário confirmou o e-mail.
     */
    public function isConfirmed(): bool
    {
        return $this->hasVerifiedEmail();
    }

    /**
     * Um utilizador (participante) pode ter muitas inscrições.
     */
    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }

    /**
     * As avaliações que este utilizador (Avaliador) fez.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Os eventos que este utilizador (Organizador) criou.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Atividades em que o utilizador está inscrito (RF_F9).
     */
    public function activities(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'activity_user');
    }
}