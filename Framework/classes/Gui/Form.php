<?php

namespace Framework\Gui;

use Framework\Gui\Exceptions\InvalidFormData;
use RuntimeException;

/**
 * HTML form handling
 *
 * @package Framework\Gui
 */
class Form extends Gui
{
    /** @var array<string, array<string, mixed>> $inputs */
    protected $inputs = [];

    /** @var string $title */
    protected $title;

    /** @var string $submit */
    protected $submit;

    /** @var string $submitColour */
    protected $submitColour;

    /** @var string $method */
    protected $method;

    /** @var array<string, mixed> $formParams */
    protected $formParams;

    /** @var mixed $result */
    public $result;

    /**
     * Initialize a form
     *
     * @param string $title Form panel heading
     * @param string $submit Submit button text
     * @param string $submitColour Submit button colour
     * @param string $method Form submit method
     * @param array<string, mixed> $formParams Additional form parameters
     */
    public function init(
        string $title = '',
        string $submit = 'Submit',
        string $submitColour = 'primary',
        string $method = 'post',
        array $formParams = []
    ): void
    {
        $this->title = $title;
        $this->submit = $submit;
        $this->submitColour = $submitColour;
        $this->method = $method;
        $this->formParams = $formParams;
    }

    /**
     * Add form input field
     *
     * @param string $name Field name
     * @param string $displayName Field display text
     * @param string $type Field type
     * @param bool $allowEmpty Allow empty values
     * @param int|string|null $value Optional default value
     * @param array<string, mixed> $options Array of additional parameters
     */
    public function input(
        string $name,
        string $displayName,
        string $type = 'text',
        bool $allowEmpty = false,
        int|string|null $value = null,
        array $options = []
    ): void
    {
        $this->inputs[$name] =
            [
                'display_name' => $displayName,
                'type' => $type,
                'allow_empty' => $allowEmpty,
                'value' => $value,
                'options' => $options
            ];
    }

    /**
     * Check for and handle submitted form
     *
     * @param array<string, mixed> $params Submitted form values
     * @param callable $function Function to process submitted data
     * @param array<mixed> $pass Array of additional parameters for function
     * @throws InvalidFormData
     */
    public function handle(array $params, callable $function, array $pass = []): void
    {
        $inputData = [];
        $inputNames = array_keys($this->inputs);

        foreach ($inputNames as $i) {
            // If it is not set or empty, is it allowed to be?
            if ((!isset($params[$i]) || $params[$i] === '') && $this->inputs[$i]['allow_empty'] === false) {
                $this->result = false;
                throw new InvalidFormData("Form parameter {$i} must be set and have a value");
            }

            // Build data array
            $inputData[$i] = $params[$i] ?? '';
        }

        $pass[] = $inputData;
        $this->result = call_user_func_array($function, $pass);
    }

    /**
     * Render form
     *
     * @param bool $inline Form fields will be inline
     * @param bool $panel Render on a bootstrap panel
     * @param bool $table Form will be in a table
     */
    public function html(bool $inline = false, bool $panel = true, bool $table = false): void
    {
        if ($inline) {
            if ($table) {
                $sepStart = '<td>';
                $sepEnd = '</td>';
            } else {
                $sepStart = $sepEnd = ' ';
            }
        } else {
            if ($table) {
                $sepStart = '<tr><td>';
                $sepEnd = '</td></tr>';
            } else {
                $sepStart = '<p>';
                $sepEnd = '</p>';
            }
        }

        ob_start();

        $this->startFormHtml();

        if ($table) {
            echo '<table class="table table-hover" border="1">';
            if ($inline) {
                echo '<tr>';
                foreach ($this->inputs as $v) {
                    if ($v['type'] !== 'hidden') {
                        echo '<th>' . $v['display_name'] . '</th>';
                    }
                }
                echo '<th></th>';

                echo '</tr><tr>';
            }
        }

        $style = '';
        if ($table && $inline) {
            $style = ' style="width: 100%;"';
        }

        foreach ($this->inputs as $k => $v) {
            if ($v['type'] !== 'hidden') {
                echo $sepStart;

                if (!($table && $inline)) {
                    echo '<strong>' . $v['display_name'] . '</strong> ';
                }

                if ($table && !$inline) {
                    echo $sepEnd;
                    echo $sepStart;
                }

                $this->inputHtml($k, $style);

                echo $sepEnd;
            } else {
                $this->inputHtml($k, $style);
            }

            if (isset($v['options']['after'])) {
                echo $v['options']['after'];
            }
        }

        echo $sepStart;
        $this->submitHtml();
        echo $sepEnd;

        if ($table) {
            if ($inline) {
                echo '</tr>';
            }

            echo '</table>';
        }

        $this->endFormHtml();

        if ($panel) {
            self::panel($this->title, ob_get_clean());
        } else {
            echo ob_get_clean();
        }
    }

    /**
     * Render form start tag
     */
    public function startFormHtml(): void
    {
        $formParams = '';
        foreach ($this->formParams as $key => $value) {
            $formParams .= " {$key}=\"{$value}\"";
        }

        echo '<form method="' . $this->method . '"' . $formParams . '>';
    }

    /**
     * Render form end tag
     */
    public function endFormHtml(): void
    {
        echo '</form>';
    }

    /**
     * Render form field
     *
     * @param string $field Field name
     * @param string $style Optional additional style values
     * @throws RuntimeException
     */
    public function inputHtml(string $field, string $style = ''): void
    {
        if (!array_key_exists($field, $this->inputs)) {
            throw new RuntimeException("Cannot render undefined form field {$field}");
        }

        if ($this->inputs[$field]['type'] === 'select') {
            echo '<select name="' . $field . '"' . $style . '>';

            foreach ($this->inputs[$field]['options']['selects'] as $sk => $sv) {
                echo '<option value="' . $sk . '"';

                if ($this->inputs[$field]['value'] == $sk) {
                    echo ' selected';
                }

                echo '>' . $sv . '</option>';
            }

            echo '</select>';
        } elseif ($this->inputs[$field]['type'] === 'radio') {
            echo ($this->inputs[$field]['options']['pre_break'] ?? false) ? '<br>' : '';
            foreach ($this->inputs[$field]['options']['radios'] as $rk => $rv) {
                $id = $rv['id'] ?? "{$field}_{$rk}";
                $value = $rv['value'] ?? '';
                $checked = $this->inputs[$field]['value'] == $value ? ' checked' : '';
                $break = ($rv['break'] ?? false) ? '<br>' : '';
                echo '<input type="radio" name="'. $field . '" id="' . $id . '" value="' . $value . '"' . $checked . $style . '>';
                echo '<label for="' . $id . '">' . $rk . '</label>' . $break;
            }
        } else {
            $extra = '';

            if ($this->inputs[$field]['value'] !== null) {
                $extra .= ' value="' . $this->inputs[$field]['value'] . '"';
            }

            if (isset($this->inputs[$field]['options']['placeholder'])) {
                $extra .= ' placeholder="' . $this->inputs[$field]['options']['placeholder'] . '"';
            }

            if ($this->inputs[$field]['options']['autofocus'] ?? false) {
                $extra .= ' autofocus';
            }

            if ($this->inputs[$field]['options']['checked'] ?? false) {
                $extra .= ' checked';
            }

            if ($this->inputs[$field]['type'] === 'textarea') {
                echo '<textarea name="' . $field . '"' . $style . '>' . ($this->inputs[$field]['value'] ?? '') . '</textarea>';
            } else {
                echo '<input type="' . $this->inputs[$field]['type'] . '" name="' . $field . '"' . $extra . $style . '>';
            }
        }
    }

    /**
     * Render submit button
     */
    public function submitHtml(): void
    {
        echo '<input class="btn btn-' . $this->submitColour . '" type="submit" value="' . $this->submit . '">';
    }
}
