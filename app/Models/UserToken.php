<?php

namespace App\Models;

use App\Models\Traits\UsesUuid;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserToken extends Model
{
    use UsesUuid;
    protected $table = 'user_token';

    protected $fillable = [
        'id',
        'user_id',
        'access_token',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeToken($query, string $token)
    {
        return $query->where('access_token', $token);
    }
}
