<?php

namespace Modules\Blog\Repositories;

use Illuminate\Database\Eloquent\Model;
use Modules\Blog\Models\User;
use Spatie\QueryBuilder\QueryBuilder;

class UserRepository
{
    protected string $model = User::class;

    public function getIndexQuery()
    {
        return QueryBuilder::for($this->model)
            ->allowedFilters(['name', 'email'])
            ->allowedSorts(['created_at', 'name'])
            ->paginate();
    }

    public function find(int $id): Model
    {
        return $this->model::findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model::create($data);
    }

    public function update(int $id, array $data): Model
    {
        $user = $this->find($id);
        $user->update($data);

        return $user;
    }

    public function delete(int $id): void
    {
        $this->model::destroy($id);
    }
}
