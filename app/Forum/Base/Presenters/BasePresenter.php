<?php

namespace App\Forum\Base\Presenters;

use Laracasts\Presenter\Presenter;
use Lang;

abstract class BasePresenter extends Presenter
{
    public function isActive()
    {
        return $this->active ? Lang::get('main.active') : Lang::get('main.inactive');
    }

    public function colorLabelActive()
    {
        return $this->active ? 'success' : 'danger';
    }
}
