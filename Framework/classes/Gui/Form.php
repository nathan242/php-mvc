<?php

namespace Framework\Gui;

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

    /** @var bool|null $result */
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
     * @param string|bool $value Optional default value
     * @param array<string, mixed> $options Array of additional parameters
     */
    public function input(
        string $name,
        string $displayName,
        string $type = 'text',
        bool $allowEmpty = false,
        $value = false,
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
     * @return bool Returns true if form submit is valid
     */
    public function handle(array $params, callable $function, array $pass = []): bool
    {
        $inputData = [];
        $inputNames = array_keys($this->inputs);

        foreach ($inputNames as $i) {
            // Is the option set?
            if (!isset($params[$i])) {
                return false;
            }

            // If it is empty, is it allowed to be?
            if ($params[$i] === '' && $this->inputs[$i]['allow_empty'] === false) {
                return false;
            }

            // Build data array
            $inputData[$i] = $params[$i];
        }

        $pass[] = $inputData;
        $this->result = call_user_func_array($function, $pass);

        return true;
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

        $formParams = '';
        foreach ($this->formParams as $key => $value) {
            $formParams .= " {$key}=\"{$value}\"";
        }

        echo '<form method="' . $this->method . '"' . $formParams . '>';
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
            if ($v['type'] === 'select') {
                echo $sepStart;

                echo '<strong>' . $v['display_name'] . '</strong><select name="' . $k . '"' . $style . '>';
                foreach ($v['options']['selects'] as $sk => $sv) {
                    echo '<option value="' . $sk . '"';
                    if ($v['value'] === $sk) {
                        echo ' selected';
                    }
                    echo '>' . $sv . '</option>';
                }
                echo '</select>';

                echo $sepEnd;
            } elseif ($v['type'] !== 'hidden') {
                $extra = '';

                if ($v['value'] !== false) {
                    $extra .= ' value="' . $v['value'] . '"';
                }

                if (isset($v['options']['placeholder'])) {
                    $extra .= ' placeholder="' . $v['options']['placeholder'] . '"';
                }

                if (isset($v['options']['autofocus'])) {
                    $extra .= ' autofocus';
                }

                echo $sepStart;

                if (!($table && $inline)) {
                    echo '<strong>' . $v['display_name'] . '</strong>';
                }

                if ($table && !$inline) {
                    echo $sepEnd;
                    echo $sepStart;
                }

                echo '<input type="' . $v['type'] . '" name="' . $k . '"' . $extra . $style . '>';
                echo $sepEnd;
            } else {
                $extra = '';

                if ($v['value'] !== false) {
                    $extra .= ' value="' . $v['value'] . '"';
                }

                echo '<input type="' . $v['type'] . '" name="' . $k . '"' . $extra . '>';
            }

            if (isset($v['options']['after'])) {
                echo $v['options']['after'];
            }
        }

        echo $sepStart;
        echo '<input class="btn btn-' . $this->submitColour . '" type="submit" value="' . $this->submit . '">';
        echo $sepEnd;

        if ($table) {
            if ($inline) {
                echo '</tr>';
            }

            echo '</table>';
        }

        echo '</form>';

        if ($panel) {
            self::panel($this->title, ob_get_clean());
        } else {
            echo ob_get_clean();
        }
    }
}
