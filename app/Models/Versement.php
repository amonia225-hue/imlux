<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Versement extends Model
{
    use Auditable;

    protected $fillable = [
        'souscription_id', 'amount', 'payment_date', 'payment_method',
        'reference', 'note', 'invoice_path',
    ];

    public function auditLabel(): string
    {
        return 'Versement de ' . number_format((float) $this->amount, 0, ',', ' ') . ' FCFA';
    }

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function souscription(): BelongsTo
    {
        return $this->belongsTo(Souscription::class);
    }
}
