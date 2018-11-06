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

        return str_limit($this->content, $lenght);
    }
}
