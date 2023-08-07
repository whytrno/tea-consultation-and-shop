<?php

namespace App\Models;

use App\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Message extends BaseModel
{
    use HasFactory;

    public function medias(): HasMany
    {
        return $this->hasMany(MessageMedia::class, 'message_id');
    }
}