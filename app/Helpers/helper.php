<?php

use App\Actions\Translations\CreateTranslation;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

if (! function_exists('isAdmin')) {
    /**
     * Returns true if current user logged in's role is admin.
     *
     * @return bool
     */
    function isAdmin()
    {
        return Auth::user() && Auth::user()->role !== 'user';
    }
}

if (! function_exists('replaceVariablesInTemplate')) {
    function replaceVariablesInTemplate($template, $variables)
    {
        if ($variables && count($variables) === 0) {
            return $template;
        }

        return preg_replace_callback(
            '#{(.*?)}#',
            function ($match) use ($variables) {
                $match[1] = trim($match[1], '$');

                return $variables[$match[1]];
            },
            ' '.$template.' '
        );
    }
}
if (! function_exists('getTranslationText')) {
    function getTranslationText($field, $model)
    {
        $langs      = collect(['fr_FR', 'en_EN', 'de_DE']);
        $lang       = CreateTranslation::language();
        $otherLangs = $langs->filter(function ($value, $key) use ($lang) {
            return $value !== $lang;
        })->values()->toArray();
        if (!array_key_exists($field, $model['translation'])) {
            return isset($model->getAttributes()[$field])&&$model->getAttributes()[$field]?$model->getAttributes()[$field]:'';
        }
        if (isset($model['translation'][$field][$lang])&&$model['translation'][$field][$lang]) {
            return $model['translation'][$field][$lang];
        }
        if (isset($model['translation'][$field][$otherLangs[0]])&&$model['translation'][$field][$otherLangs[0]]) {
            return $model['translation'][$field][$otherLangs[0]];
        }
        if (isset($model['translation'][$field][$otherLangs[1]])&&$model['translation'][$field][$otherLangs[1]]) {
            return $model['translation'][$field][$otherLangs[1]];
        }
        return '';

    }
}
if (! function_exists('translateObject')) {
   function translateObject(string $field, $model): string
   {
        if (!$model) {
            return '';
        }
        $output = getTranslationText($field, $model);
        return $output === 'null' ? '' : $output;
   }
}
