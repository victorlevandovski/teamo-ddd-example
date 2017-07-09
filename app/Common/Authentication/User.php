<?php

namespace Teamo\Common\Authentication;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * This User model is here because it is an easy way to use Laravel authentication
 * by duplicating User entity as Eloquent model. Think of it as a small bounded
 * context.
 */
class User extends Authenticatable
{
    use Notifiable;

    public $incrementing = false;
}
