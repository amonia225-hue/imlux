<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ilot extends Model
{
    use Auditable;

    protected $fillable = [
        'programme_id', 'name', 'description', 'ordre',
    ];

    public function auditLabel(): string
    {
        return 'Îlot ' . $this->name;
    }

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class)->orderBy('reference');
    }

    public function nbLots(): int
    {
        return $this->relationLoaded('lots') ? $this->lots->count() : $this->lots()->count();
    }
}
