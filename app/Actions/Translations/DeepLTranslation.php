<?php

namespace App\Actions\Translations;

use App\Models\BaseModel;
use App\Models\Translation;
use BabyMarkt\DeepL\DeepL;

class DeepLTranslation
{
    /**
     * @var \BabyMarkt\DeepL\DeepL
     */
    private $deepL;

    /**
     * @var \App\Models\Translation
     */
    private $translation;

    public function __construct(BaseModel $model, string $key)
    {
        $this->deepL = new DeepL(config('deepl.auth_key'), 2, 'api-free.deepl.com');
        $this->translation = $this->getTranslation($model, $key);
    }

    public static function execute(BaseModel $model, string $key): void
    {
        $deepL = new self($model, $key);
        if (! $deepL->translation) {
            return;
        }
        $deepL->translation->fr_FR = $deepL->translate('fr');
        $deepL->translation->en_EN = $deepL->translate('en');
        $deepL->translation->de_DE = $deepL->translate('de');
        $deepL->translation->save();
    }

    /**
     * @throws \BabyMarkt\DeepL\DeepLException
     */
    public function translate($target)
    {
        return $this->output($this->deepL->translate(
            $this->text(),
            $this->source(),
            $target
        ));
    }

    private function output($response)
    {
        return $response[0]['text'];
    }

    private function source()
    {
        return substr($this->translation->language, 0, 2);
    }

    private function text()
    {
        return $this->translation[$this->translation->language];
    }

    public static function getTranslation(BaseModel $model, $key)
    {
        return Translation::where('type', $model->getTable())
            ->where('type_id', $model->id)
            ->where('key', $key)
            ->first();
    }
}
