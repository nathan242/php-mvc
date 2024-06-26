<?php

namespace Framework\Gui;

class Gui
{
    /**
     * Output a bootstrap panel
     *
     * @param string $heading Panel heading
     * @param string $content Panel content
     * @param string $colour Panel colour
     * @param array<string, mixed> $options Array of additional parameters
     */
    public static function panel(string $heading, string $content, string $colour = 'primary', array $options = []): void
    {
        $class = (isset($options['class'])) ? ' ' . $options['class'] : ' panel-custom';
        echo '<div class="panel panel-' . $colour . $class . '">';
        if ($heading !== false) {
            echo '<div class="panel-heading">' . $heading . '</div>';
        }
        echo '<div class="panel-body">' . $content . '</div>';
        echo '</div>';
    }

    /**
     * Output a bootstrap table
     *
     * @param array<mixed> $data Array containing table data
     * @param array<mixed>|bool $headings Optional array of table headers
     * @param array<string, mixed>|bool $select Configuration array for table row link
     * @param array<string, array<string, mixed>>|bool $buttons Configuration array for table row buttons
     * @return bool|void False if no table data
     */
    public static function table(array $data, array|bool $headings = false, $select = false, $buttons = false)
    {
        if (!is_array($data) || !isset($data[0])) {
            return false;
        }

        echo '<table class="table table-hover">';

        // Headings
        echo '<tr>';
        if (is_array($headings)) {
            echo '<th>' . implode('</th><th>', $headings) . '</th>';
        } else {
            echo '<th>' . implode('</th><th>', array_keys($data[0])) . '</th>';
        }

        if (is_array($buttons)) {
            echo '<th></th>';
        }

        echo '</tr>';

        // Data
        foreach ($data as $v) {
            if (is_array($select)) {
                if (array_key_exists('route', $select)) {
                    $url = $select['route'] . $v[$select['vkey']];
                } else {
                    $sep = (strpos($select['path'], '?')) ? '&' : '?';
                    $url = $select['path'] . $sep . $select['key'] . '=' . $v[$select['vkey']];
                }
                echo '<tr onclick="window.location=\'' . $url . '\'">';
            } else {
                echo '<tr>';
            }

            echo '<td>' . implode('</td><td>', $v) . '</td>';

            if (is_array($buttons)) {
                echo '<td>';
                foreach ($buttons as $b) {
                    if (!isset($b['colour'])) {
                        $b['colour'] = 'primary';
                    }
                    if (!isset($b['prompt'])) {
                        $b['prompt'] = false;
                    }
                    if (!isset($b['options'])) {
                        $b['options'] = array();
                    }
                    if (!isset($b['options']['params'])) {
                        $b['options']['params'] = array();
                    }

                    if (isset($b['vkey'])) {
                        $b['options']['params']['vkey'] = $v[$b['vkey']];
                    }

                    if (isset($b['path'])) {
                        $url = $b['path'];

                        if (isset($b['vkey'])) {
                            $url = preg_replace('/{vkey}/', $v[$b['vkey']], $url);

                            if (isset($b['key'])) {
                                $sep = (strpos($b['path'], '?')) ? '&' : '?';
                                $url .= $sep . $b['key'] . '=' . $v[$b['vkey']];
                            }
                        }


                    } else {
                        $url = '';
                    }

                    self::button($b['text'], $url, $b['colour'], $b['prompt'], $b['options']);
                    echo ' ';
                }
                echo '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    /**
     * Output a bootstrap button
     *
     * @param string $text Button text
     * @param string $link Button link URL
     * @param string $colour Button colour
     * @param bool $prompt Prompt for confirmation
     * @param array<string, mixed> $options Array of additional parameters
     */
    public static function button(string $text, string $link, string $colour = 'primary', bool $prompt = false, array $options = []): void
    {
        if ($prompt !== false) {
            $dest = ' onclick="if (confirm(\'' . $prompt . '\')) { window.location = \'' . $link . '\' }"';
        } elseif (!isset($options['onclick'])) {
            $dest = ' href="' . $link . '"';
        } else {
            $dest = '';
        }

        $extra = '';
        if (isset($options['onclick'])) {
            $onclick = preg_replace('/\"/', '\\\"', $options['onclick']);
            if (isset($options['params'])) {
                foreach ($options['params'] as $pk => $pv) {
                    $onclick = preg_replace('/{' . $pk . '}/', $pv, $onclick);
                }
            }
            $extra .= ' onclick="' . $onclick . '"';
        }

        if (isset($options['target'])) {
            $extra .= ' target="' . $options['target'] . '"';
        }

        echo '<a class="btn btn-' . $colour . '"' . $dest . $extra . '>' . $text . '</a>';
    }
}
