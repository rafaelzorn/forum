<?php

namespace App\Forum\User\Presenters;

use App\Forum\Base\Presenters\BasePresenter;

class UserPresenter extends BasePresenter
{
    public function firstLetterName()
    {
        return substr($this->name, 0, 1);
    }
}
