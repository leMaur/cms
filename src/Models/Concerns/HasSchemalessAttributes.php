<?php

namespace Lemaur\Cms\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\SchemalessAttributes;

/**
 * @property SchemalessAttributes $extra_attributes
 */
trait HasSchemalessAttributes
{
    public function getExtraAttributesAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'extra_attributes');
    }

    public function getExtraAttribute(string $name, $default = null): mixed
    {
        return $this->extra_attributes->get($name, $default);
    }

    public function setExtraAttribute(string $name, $value): void
    {
        $this->extra_attributes->set($name, $value);

        $this->save();
    }

    public function hasExtraAttribute(string $name): bool
    {
        return $this->extra_attributes->get($name) !== null;
    }

    public function forgetExtraAttribute(string $name): void
    {
        $this->extra_attributes->forget($name);

        $this->save();
    }

    public function fillExtraAttributes(array $attributes): void
    {
        foreach ($attributes as $name => $value) {
            $this->extra_attributes->set($name, $value);
        }

        $this->save();
    }
}
