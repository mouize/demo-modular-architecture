<?php

namespace Modules\Blog\Repositories;

use Modules\Blog\Models\Post;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PostRepository
{
    public function getIndexQuery()
    {
        return QueryBuilder::for(Post::class)
            ->allowedFilters([
                AllowedFilter::exact('user_id'),
                AllowedFilter::partial('title'),
            ])
            ->allowedIncludes(['user', 'comments'])
            ->allowedSorts(['created_at', 'title'])
            ->paginate();
    }

    public function find(int $id): Post
    {
        return Post::with(['user', 'comments'])->findOrFail($id);
    }

    public function create(array $data): Post
    {
        return Post::create($data);
    }

    public function update(int $id, array $data): Post
    {
        $post = $this->find($id);
        $post->update($data);

        return $post;
    }

    public function delete(int $id): void
    {
        Post::destroy($id);
    }
}
