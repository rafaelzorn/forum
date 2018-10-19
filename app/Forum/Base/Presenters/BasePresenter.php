<?php

namespace App\Forum\Base\Presenters;

use Laracasts\Presenter\Presenter;

abstract class BasePresenter extends Presenter
{
    public function isActive()
    {
        return $this->active ? 'Active' : 'Inactive';
    }

    public function colorLabelActive()
    {
        return $this->active ? 'success' : 'danger';
    }
}
