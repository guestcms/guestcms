<?php

namespace Guestcms\Contact\Models;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Models\BaseModel;
use Guestcms\Contact\Enums\CustomFieldType;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomField extends BaseModel
{
    protected $table = 'contact_custom_fields';

    protected $fillable = [
        'name',
        'required',
        'placeholder',
        'type',
        'status',
        'order',

    ];

    protected $casts = [
        'type' => CustomFieldType::class,
        'required' => 'bool',
        'order' => 'int',
        'status' => BaseStatusEnum::class,
    ];

    protected static function booted(): void
    {
        static::deleting(fn (CustomField $customField) => $customField->options()->delete());
    }

    public function options(): HasMany
    {
        return $this->hasMany(CustomFieldOption::class, 'custom_field_id');
    }

    public function saveOptions(array $options): void
    {
        $formattedOptions = [];

        $this
            ->options()
            ->whereNotIn('id', array_column($options, 'id'))
            ->delete();

        foreach ($options as $item) {
            $option = null;

            if (isset($item['id'])) {
                $option = $this->options()->find($item['id']);
                $option->fill($item);
            }

            if (! $option) {
                $option = new CustomFieldOption($item);
            }

            if ($option->isDirty()) {
                $formattedOptions[] = $option;
            }
        }

        if (! empty($formattedOptions)) {
            $this->options()->saveMany($formattedOptions);
        }
    }
}
