<?php

namespace Modules\Blog\Http\Controllers;

use Modules\Blog\Http\Requests\StorePostRequest;
use Modules\Blog\Http\Requests\UpdatePostRequest;
use Modules\Blog\Http\Resources\PostResource;
use Modules\Blog\Repositories\PostRepository;

class PostController
{
    public function __construct(protected PostRepository $repository) {}

    public function index()
    {
        return PostResource::collection($this->repository->getIndexQuery());
    }

    public function show($id)
    {
        return new PostResource($this->repository->find($id));
    }

    public function store(StorePostRequest $request)
    {
        return new PostResource($this->repository->create($request->validated()));
    }

    public function update(UpdatePostRequest $request, $id)
    {
        return new PostResource($this->repository->update($id, $request->validated()));
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return response()->json(['message' => 'Deleted']);
    }
}
