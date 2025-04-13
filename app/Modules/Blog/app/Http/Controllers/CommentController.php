<?php

namespace Modules\Blog\Http\Controllers;

use Modules\Blog\Http\Requests\StoreCommentRequest;
use Modules\Blog\Http\Requests\UpdateCommentRequest;
use Modules\Blog\Http\Resources\CommentResource;
use Modules\Blog\Repositories\CommentRepository;

class CommentController
{
    public function __construct(protected CommentRepository $repository) {}

    public function index()
    {
        return CommentResource::collection($this->repository->getIndexQuery());
    }

    public function show($id)
    {
        return new CommentResource($this->repository->find($id));
    }

    public function store(StoreCommentRequest $request)
    {
        return new CommentResource($this->repository->create($request->validated()));
    }

    public function update(UpdateCommentRequest $request, $id)
    {
        return new CommentResource($this->repository->update($id, $request->validated()));
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return response()->noContent();
    }
}
