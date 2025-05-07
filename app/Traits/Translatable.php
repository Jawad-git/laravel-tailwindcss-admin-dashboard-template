namespace App\Traits;

use App\Models\Translation;

trait Translatable
{
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /**
     * Get the translated value for a given key and locale,
     * with optional fallback.
     */
    public function translate(string $key, string $locale, $fallback = null)
    {
        $t = $this->translations
                  ->where('key', $key)
                  ->where('locale', $locale)
                  ->first();

        return $t ? $t->value : $fallback;
    }
}
