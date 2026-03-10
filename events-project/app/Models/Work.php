<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // 👈 ADICIONE ESTE IMPORT
use Illuminate\Database\Eloquent\Relations\HasOne;

class Work extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_type_id',
        'title',
        'abstract',
        'advisor',
        'co_authors_text',
        'file_path',
        'presentation_date',
        'presentation_room',
        'presentation_order',
    ];

    // ... (as suas funções user(), workType(), e inscription() existentes ficam aqui) ...
    
    /**
     * Um trabalho pertence a um Utilizador (Autor).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Um trabalho pertence a um Tipo de Trabalho.
     */
    public function workType(): BelongsTo
    {
        return $this->belongsTo(WorkType::class);
    }

    /**
     * Um trabalho está ligado a uma Inscrição.
     */
    public function inscription(): HasOne
    {
        return $this->hasOne(Inscription::class);
    }

    /**
     * 👇 FUNÇÃO ADICIONADA 👇
     * Um trabalho pode ter muitas avaliações (reviews).
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}