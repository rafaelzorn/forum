<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Forum\Topic\Repositories\Contracts\TopicRepositoryInterface;
use App\Forum\Category\Repositories\Contracts\CategoryRepositoryInterface;
use App\Forum\Topic\Services\TopicService;
use App\Http\Requests\Topic\TopicRequest;

class TopicsController extends Controller
{
    private $currentPage;
    private $topicRepository;
    private $categoryRepository;
    private $topicService;

    public function __construct(
        TopicRepositoryInterface $topicRepository,
        CategoryRepositoryInterface $categoryRepository,
        TopicService $topicService
    )
	{
        $this->currentPage = 'topics';
        $this->topicRepository = $topicRepository;
        $this->categoryRepository = $categoryRepository;
        $this->topicService = $topicService;
	}

    public function index()
    {
        $currentPage = $this->currentPage;
        $topics  = $this->topicRepository->all();

        return view('manager.topics.index')->with(compact(
            'currentPage',
            'topics'
        ));
    }

    public function create()
    {
        $currentPage = $this->currentPage;
        $edit        = false;
        $topic       = $this->topicRepository;
        $categories  = $this->categoryRepository->all();

        return view('manager.topics.form')->with(compact(
            'currentPage',
            'edit',
            'topic',
            'categories'
        ));
    }

    public function store(TopicRequest $request)
    {
        $request = $this->topicService->store($request->except('_token', '_method'));

        session()->flash('message',[
            'type' 	  => $request['type'],
			'message' => $request['message']
        ]);

        return redirect()->route('manager.topics.index');
    }

    public function edit($id)
    {
        $currentPage = $this->currentPage;
        $edit        = true;
        $topic       = $this->topicRepository->findOrFail($id);
        $categories  = $this->categoryRepository->all();

        return view('manager.topics.form')->with(compact(
            'currentPage',
            'edit',
            'topic',
            'categories'
        ));
    }

    public function update(TopicRequest $request, $id)
    {
        $request = $this->topicService->update($request->except('_token', '_method'), $id);

        session()->flash('message',[
            'type' 	  => $request['type'],
			'message' => $request['message']
        ]);

        return redirect()->route('manager.topics.index');
    }

    public function destroy($id)
    {
        $request = $this->topicService->destroy($id);

        session()->flash('message',[
            'type' 	  => $request['type'],
			'message' => $request['message']
        ]);

        return redirect()->route('manager.topics.index');
    }
}
