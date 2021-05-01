<?php

declare(strict_types=1);

namespace Lemaur\Cms\Tests\Feature;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Lemaur\Cms\Models\Concerns\HasPages;

class User extends Model implements AuthorizableContract, AuthenticatableContract
{
    use Authenticatable;
    use Authorizable;
    use HasPages;

    protected $fillable = ['email'];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        (new static)->setTable((string) config('cms.users.table'));
    }
}
