<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestTravel extends Model
{
    use HasFactory;

    protected $table = 'request_travel';

    protected $fillable = [
        'user_id',
        'nome_solicitante',
        'destino',
        'data_ida',
        'data_volta',
        'status',
    ];

    protected $casts = [
        'data_ida' => 'date',
        'data_volta' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
