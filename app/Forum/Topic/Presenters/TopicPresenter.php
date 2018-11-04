<?php

namespace App\Forum\Topic\Presenters;

use App\Forum\Base\Presenters\BasePresenter;

class TopicPresenter extends BasePresenter
{
    public function cutContent($lenght)
    {
        if (strlen($this->content) <= $lenght) {
            return $this->content;
        }

        return substr($this->content, 0, $lenght) . '...';
    }
}
