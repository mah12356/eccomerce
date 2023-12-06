<?php

namespace common\components;

class Navigator
{
    protected array $ignoredUrls = [
        '/diet/find-package-diets',
        '/diet/download-regime',
        '/factor/buy-package',
        '/factor/pay-factor',
        '/factor/iran-kish-header',
        '/factor/payment-result',
        '/factor/delete',
        '/packages/send-comment',
        '/packages/find-package-courses',
        '/site/login',
        '/site/signup',
        '/site/verify',
        '/site/logout',
        '/site/error',
        '/site/captcha',
        '/site/navigate',
        '/ticket/download-content',
    ];

    public function setUrl(Stack $stack, string $url)
    {
        $explodedUrl = explode('?', $url);
        if (!isset($explodedUrl[0]) || !$explodedUrl[0]) {
            return false;
        }

        $baseUrl = $explodedUrl[0];

        if (in_array($baseUrl, $this->ignoredUrls)) {
            return false;
        }

        $variables = [];
        if (isset($explodedUrl[1]) && $explodedUrl[1]) {
            $variables = self::variablesToArray($explodedUrl[1]);
        }

        $stack->push([$baseUrl, $variables], false);
        return 1;
    }

    public static function getUrl($data): array
    {
        $url = [];

        if (!isset($data[0]) || !$data[0]) {
            return ['/site/index'];
        }

        $url[] = $data[0];

        if (!isset($data[1]) || !$data[1]) {
            return $url;
        }

        foreach ($data[1] as $item) {
            $url[array_keys($item)[0]] = $item[array_keys($item)[0]];
        }

        return $url;
    }

    public static function variablesToArray($data): array
    {
        $variables = [];

        $data = explode('&', $data);
        foreach ($data as $item) {
            $variable = explode('=', $item);
            if (isset($variable[0]) && $variable[0] && isset($variable[1])) {
                $variables[] = [$variable[0] => $variable[1]];
            }
        }

        return $variables;
    }
}