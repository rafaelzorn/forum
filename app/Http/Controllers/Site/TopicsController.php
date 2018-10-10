<?php

namespace App\Http\Controllers\Site;

use App\Forum\Category\Repositories\Contracts\CategoryRepositoryInterface;
use App\Forum\Topic\Repositories\Contracts\TopicRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TopicsController extends Controller
{
    private $categoryRepository;
    private $topicRepository;

    public function __construct (
        CategoryRepositoryInterface $categoryRepository,
        TopicRepositoryInterface $topicRepository
    )
	{
        $this->categoryRepository = $categoryRepository;
        $this->topicRepository = $topicRepository;
	}

    public function index(Request $request)
    {
        $filters = $request->all();

        $categories = $this->categoryRepository->getActives();
        $topics     = $this->topicRepository->filter($filters, 15, true);

        return view('site.topics.index')->with(compact(
            'categories',
            'topics',
            'filters'
        ));
    }

    public function show($slug)
    {
        $topic = $this->topicRepository->findOrFailBy(['slug' => $slug]);

        return view('site.topics.show')->with(compact(
            'topic'
        ));
    }
}
