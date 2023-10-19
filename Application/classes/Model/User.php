<?php

namespace Application\Model;

use Framework\Model\Model;

/**
 * User table model
 *
 * @package Application\Model
 * @property int $id
 * @property string $username
 * @property string $password
 * @property int $enabled
 */
class User extends Model
{
    /** @var string $table */
    protected $table = 'users';
}
