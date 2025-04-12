<?php

namespace Modules\Blog\Repositories;

use Modules\Blog\Models\Comment;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CommentRepository
{
    public function getIndexQuery()
    {
        return QueryBuilder::for(Comment::class)
            ->allowedFilters([
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('post_id'),
                AllowedFilter::partial('content'),
            ])
            ->allowedIncludes(['user', 'post'])
            ->allowedSorts(['created_at'])
            ->paginate();
    }

    public function find(int $id): Comment
    {
        return Comment::with(['user', 'post'])->findOrFail($id);
    }

    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    public function update(int $id, array $data): Comment
    {
        $comment = $this->find($id);
        $comment->update($data);

        return $comment;
    }

    public function delete(int $id): void
    {
        Comment::destroy($id);
    }
}
