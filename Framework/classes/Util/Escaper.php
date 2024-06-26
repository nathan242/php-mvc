<?php

namespace Framework\Util;

/**
 * Data escape functions
 *
 * @package Framework\Util
 */
class Escaper
{
    /**
     * Escapes text for safe use in HTML
     *
     * @param string $text Text to escape
     * @return string
     */
    public static function escapeHtml(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES);
    }

    /**
     * Escapes keys and values for safe use in HTML
     *
     * @param array<mixed> $arr Array with values to escape
     * @return array<mixed>
     */
    public static function escapeHtmlArray(array $arr): array
    {
        $output = [];

        foreach ($arr as $k => $v) {
            if (is_string($k)) {
                $k = self::escapeHtml($k);
            }

            if (is_array($v)) {
                $v = self::escapeHtmlArray($v);
            } elseif (is_string($v)) {
                $v = self::escapeHtml($v);
            }

            $output[$k] = $v;
        }

        return $output;
    }
}
