<?php

namespace Application\Model;

use Framework\Model\Model;

/**
 * Test table model
 *
 * @package Application\Model
 * @property int $id
 * @property string $text
 * @property int $number
 */
class Test extends Model
{
    /** @var string $table */
    protected $table = 'test';
}

