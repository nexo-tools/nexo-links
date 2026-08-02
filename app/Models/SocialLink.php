<?php

namespace App\Models;

use Database\Factories\SocialLinkFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $page_id
 * @property string $platform
 * @property string $value
 * @property int $position
 */
#[Fillable(['platform', 'value', 'position'])]
class SocialLink extends Model
{
    /** @use HasFactory<SocialLinkFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $touches = ['page'];

    /**
     * @return BelongsTo<Page, $this>
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Public URL built from the platform template.
     */
    public function url(): string
    {
        $platform = config('nexo.social_platforms.'.$this->platform);

        $value = match ($platform['type']) {
            'handle' => rawurlencode($this->value),
            // wa.me wants the number without the leading +
            'phone' => $this->platform === 'whatsapp' ? ltrim($this->value, '+') : $this->value,
            // email and url values are already validated on input
            default => $this->value,
        };

        return str_replace('{value}', $value, $platform['url']);
    }

    public function label(): string
    {
        return config('nexo.social_platforms.'.$this->platform.'.label', $this->platform);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        // Link::position casts to int and this one did not, so the same field
        // came back as a string here and as a number there — the kind of
        // asymmetry that turns into a === comparison bug months later.
        return [
            'position' => 'integer',
        ];
    }
}
