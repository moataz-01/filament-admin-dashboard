<?php

namespace App\Traits;

use Illuminate\Support\Facades\Config;
use Filament\Forms\Components\{Tabs, TextInput, Textarea};

trait HasTranslatableFields
{
    /**
     * Build tabs for translatable fields in a form.
     *
     * @param array|string $fields Array of field configs or single field name
     *                            When array, each element should be an array with:
     *                            - 'name' (string): The field name/key
     *                            - 'type' (string): Input type ('text', 'textarea')
     *                            - 'rules' (array): Validation rules
     *                            When string, creates a simple text field with that name
     * @return array Array of Filament Tabs\Tab components, one for each configured locale
     */
    public static function buildLocaleTabs(array|string $fields): array
    {
        $tabs = [];
        $fields = self::normalizeFields($fields);

        foreach (Config::get('app.available_locales', ['en' => 'English']) as $locale => $label) {
            $tabs[] = Tabs\Tab::make(trans('general.' . $label))
                ->schema(self::buildTabSchema($fields, $locale));
        }

        return $tabs;
    }

    /**
     * Normalize fields into a standard format.
     *
     * @param array|string $fields
     * @return array
     */
    private static function normalizeFields(array|string $fields): array
    {
        return is_string($fields) ? [['name' => $fields, 'type' => 'text']] : $fields;
    }

    /**
     * Build schema for a specific tab.
     *
     * @param array $fields
     * @param string $locale
     * @return array
     */
    private static function buildTabSchema(array $fields, string $locale): array
    {
        $schema = [];

        foreach ($fields as $field) {
            $fieldName = $field['name'] ?? '';
            $fieldType = $field['type'] ?? 'text';
            $fieldRules = $field['rules'] ?? [];

            $localeRules = self::getValidationRulesForLocale($locale, $fieldRules);
            $formField = self::createFormField($fieldName, $locale, $fieldType, $localeRules);

            $schema[] = self::applyRequiredRule($formField, $localeRules, $fieldName);
        }

        return $schema;
    }

    /**
     * Create a form field based on type.
     *
     * @param string $fieldName
     * @param string $locale
     * @param string $fieldType
     * @param array $localeRules
     * @return \Filament\Forms\Components\Field
     */
    private static function createFormField(string $fieldName, string $locale, string $fieldType, array $localeRules)
    {
        $fieldClass = $fieldType === 'textarea' ? Textarea::class : TextInput::class;

        return $fieldClass::make("{$fieldName}.{$locale}")
            ->label(__('general.' . $fieldName))
            ->rules($localeRules);
    }

    /**
     * Apply required rule to a form field if applicable.
     *
     * @param \Filament\Forms\Components\Field $formField
     * @param array $localeRules
     * @param string $fieldName
     * @return \Filament\Forms\Components\Field
     */
    private static function applyRequiredRule($formField, array $localeRules, string $fieldName)
    {
        return in_array('required', $localeRules)
            ? $formField->markAsRequired()
            : $formField;
    }

    /**
     * Get validation rules for a specific locale.
     *
     * @param string $locale
     * @param array $additionalRules
     * @return array
     */
    public static function getValidationRulesForLocale(string $locale, array $additionalRules = []): array
    {
        $patterns = self::getLocalePatterns();

        $pattern = $patterns[$locale] ?? $patterns['default'];

        return array_merge($additionalRules, ["regex:$pattern"]);
    }

    /**
     * Get predefined regex patterns for locales.
     *
     * @return array
     */
    private static function getLocalePatterns(): array
    {
        return [
            'ar' => '/^[\s\p{Arabic}0-9]+$/u',            // Arabic
            'zh' => '/^[\s\p{Han}0-9]+$/u',              // Chinese
            'hi' => '/^[\s\p{Devanagari}0-9]+$/u',       // Hindi
            'bn' => '/^[\s\p{Bengali}0-9]+$/u',          // Bengali
            'ru' => '/^[\s\p{Cyrillic}0-9]+$/u',         // Russian
            'ur' => '/^[\s\p{Arabic}0-9]+$/u',           // Urdu
            'ja' => '/^[\s\p{Hiragana}\p{Katakana}\p{Han}0-9]+$/u',  // Japanese
            'ko' => '/^[\s\p{Hangul}0-9]+$/u',           // Korean
            'th' => '/^[\s\p{Thai}0-9]+$/u',             // Thai
            'he' => '/^[\s\p{Hebrew}0-9]+$/u',           // Hebrew
            'el' => '/^[\s\p{Greek}0-9]+$/u',            // Greek
            'ka' => '/^[\s\p{Georgian}0-9]+$/u',         // Georgian
            'am' => '/^[\s\p{Ethiopic}0-9]+$/u',         // Amharic
            'default' => '/^[\s\p{Latin}0-9]+$/u',       // Latin-based languages
        ];
    }
}
