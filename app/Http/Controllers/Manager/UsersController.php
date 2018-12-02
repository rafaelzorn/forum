<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Forum\User\Repositories\Contracts\UserRepositoryInterface;
use App\Forum\User\Services\UserService;
use App\Http\Requests\User\UserRequest;

class UsersController extends Controller
{
    private $currentPage;
    private $userRepository;
    private $userService;

    public function __construct (
        UserRepositoryInterface $userRepository,
        UserService $userService
    )
	{
        $this->currentPage = 'users';
        $this->userRepository = $userRepository;
        $this->userService = $userService;
	}

    public function index()
    {
        $currentPage = $this->currentPage;
        $users = $this->userRepository->all();

        return view('manager.users.index')->with(compact(
            'currentPage',
            'users'
        ));
    }

    public function create()
    {
        $currentPage = $this->currentPage;
        $edit        = false;
        $user        = $this->userRepository;

        return view('manager.users.form')->with(compact(
            'currentPage',
            'edit',
            'user'
        ));
    }

    public function store(UserRequest $request)
    {
        $request = $this->userService->store($request->except('_token', '_method'));

        session()->flash('message',[
            'type' 	  => $request['type'],
			'message' => $request['message']
        ]);

        return redirect()->route('manager.users.index');
    }

    public function edit($id)
    {
        $currentPage = $this->currentPage;
        $edit        = true;
        $user        = $this->userRepository->findOrFail($id);

        return view('manager.users.form')->with(compact(
            'currentPage',
            'edit',
            'user'
        ));
    }

    public function update(UserRequest $request, $id)
    {
        $request = $this->userService->update($request->except('_token', '_method'), $id);

        session()->flash('message',[
            'type' 	  => $request['type'],
			'message' => $request['message']
        ]);

        return redirect()->route('manager.users.index');
    }

    public function destroy($id)
    {
        $request = $this->userService->destroy($id);

        session()->flash('message',[
            'type' 	  => $request['type'],
			'message' => $request['message']
        ]);

        return redirect()->route('manager.users.index');
    }
}
