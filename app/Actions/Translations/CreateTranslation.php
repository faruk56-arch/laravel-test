<?php

namespace App\Actions\Translations;

use App;
use App\Models\BaseModel;
use App\Models\Translation;

class CreateTranslation
{
    public static function create(string $key, string $value, BaseModel $model, $lang = null): void
    {
        $obj = new self();

        $query = $obj->query($key, $model, $value, $lang);

        $obj->quietCreate($query);
    }

    public static function update(string $key, string $value, BaseModel $model, $lang = null): void
    {
        $translation = self::findTranslation($key, $model);

        if (! $translation) {
            self::create($key, $value, $model, $lang);
            return;
        }
        $currLang = $lang ? $lang.'_'.strtoupper($lang) : $translation->language;
//        if ($lang) {
//            $translation->update(['language' => $currLang]);
//        }
        $translation->update([$currLang => $value]);
    }
    public static function updateBaseLang(string $key, string $value, BaseModel $model){
        $translation = self::findTranslation($key, $model);
        $translation->update(['language' => $value]);
    }
    private function query(string $key, BaseModel $model, string $value, $lang = null): array
    {
        $currLang = $lang ? $lang.'_'.strtoupper($lang) : self::language();
        return [
            'language' => $currLang,
            'key' => $key,
            'type'    => $model->getTable(),
            'type_id' => $model->id,
            $currLang => $value,
        ];
    }

    public static function language(): string
    {
        $allowedLocales = [
            'fr',
            'en',
            'de'
        ];

        $currentLocale = App::getLocale();
        if (! in_array($currentLocale, $allowedLocales)) {
            $currentLocale = 'en';
        }

        return $currentLocale.'_'.strtoupper($currentLocale);
    }

    private function quietCreate(array $query): void
    {
        Translation::withoutEvents(function () use ($query) {
            Translation::create($query);
        });
    }

    public static function findTranslation(string $key, BaseModel $model)
    {
        return Translation::where(['type' => $model->getTable(), 'type_id' => $model->id, 'key' => $key])->first();
    }
}
