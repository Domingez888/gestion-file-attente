<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    protected $table ='payements';
    protected $fillable = [
    'montant',
    'date',
    'statut',
    'methode',
    'service_id',
    'reference',
    'flutterwave_charge_id',
    'flutterwave_customer_id',
    'flutterwave_payment_method_id',
    'devise',
    'charge_id',
    'client_id',
];
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}