<?php

namespace App\Forum\User\Services;

use App\Forum\User\Repositories\Contracts\UserRepositoryInterface;
use Exception;
use Hash;
use Lang;

class UserService
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function store($data)
    {
        try {
            $data = array_set($data, 'password', Hash::make($data['password']));

            $this->userRepository->create($data);

            return [
                'type' => 'success',
                'message' => Lang::get('messages.user_successfully_registered'),
            ];
        } catch (Exception $e) {
            return [
                'type' => 'error',
                'message' => Lang::get('messages.user_error_registered'),
            ];
        }
    }

    public function update($data, $id)
    {
        try {
            $this->userRepository->findOrFail($id);

            $password = $this->userRepository->password;

            if (!is_null($data['password'])) {
                $password = Hash::make($data['password']);
            }

            $data = array_set($data, 'password', $password);

            $this->userRepository->update($data);

            return [
                'type' => 'success',
                'message' => Lang::get('messages.user_successfully_updated'),
            ];
        } catch (Exception $e) {
            return [
                'type' => 'error',
                'message' => Lang::get('messages.user_error_updated'),
            ];
        }
    }

    public function destroy($id)
    {
        try {
            $this->userRepository->findOrFail($id);
            $this->userRepository->delete();

            return [
                'type' => 'success',
                'message' => Lang::get('messages.user_deleted_successfully'),
            ];
        } catch (Exception $e) {
            return [
                'type' => 'error',
                'message' => Lang::get('messages.user_deleted_error'),
            ];
        }
    }
}
