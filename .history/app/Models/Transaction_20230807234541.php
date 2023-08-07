<?php

namespace App\Models;

use App\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends BaseModel
{
    use HasFactory;

    public function products(): HasMany
    {
        return $this->hasMany(TransactionProduct::class, 'transaction_id');
    }
}