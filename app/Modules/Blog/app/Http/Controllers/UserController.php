<?php

namespace Modules\Blog\Http\Controllers;

use Modules\Blog\Http\Requests\StoreUserRequest;
use Modules\Blog\Http\Requests\UpdateUserRequest;
use Modules\Blog\Http\Resources\UserResource;
use Modules\Blog\Repositories\UserRepository;

class UserController
{
    public function __construct(protected UserRepository $repository) {}

    public function index()
    {
        return UserResource::collection($this->repository->getIndexQuery());
    }

    public function show($id)
    {
        return new UserResource($this->repository->find($id));
    }

    public function store(StoreUserRequest $request)
    {
        return new UserResource($this->repository->create($request->validated()));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        return new UserResource($this->repository->update($id, $request->validated()));
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return response()->json(['message' => 'Deleted']);
    }
}
