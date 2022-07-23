<?php

namespace App\Services\MergeModels;

class SendInBlueData
{
    /**
     * @var string
     */
    private $templateId;

    /**
     * @var array
     */
    private $params;

    /**
     * @var array
     */
    private $attachment;

    public function __construct(string $templateId, array $params = null, array $attachment = null)
    {
        $this->templateId = $templateId;
        $this->params = $params;
        $this->attachment = $attachment;
    }

    public static function factory(string $templateId, array $params = null, array $attachment = null): SendInBlueData
    {
        return new self($templateId, $params, $attachment);
    }
}
