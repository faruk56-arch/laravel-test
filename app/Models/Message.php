<?php

namespace App\Models;

use App\Actions\Translations\CreateTranslation;
use Carbon\Carbon;
use OwenIt\Auditing\Auditable;

class Message extends BaseModel implements \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;
    protected $fillable
        = [
            'sender', 'recipient', 'template_id', 'params', 'type', 'type_id', 'checked', 'read', 'email',
        ];

    protected $with = ['template'];

    protected $casts = ['params' => 'array'];

    protected $appends = ['translation'];

    public function getTranslationAttribute()
    {
        return replaceVariablesInTemplate(
            array_key_exists('value', $this->template->translation) ? $this->template->translation['value'][CreateTranslation::language()] : null,
            $this->params
        );
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient');
    }

    public function template()
    {
        return $this->belongsTo(MessageTemplate::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d M Y \à H\hi');
    }
}
