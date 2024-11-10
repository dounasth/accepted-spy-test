<?php

namespace App\Application\Queries;

use App\Application\Contracts\ListSpiesQueryContract;
use App\Domain\Repositories\SpyRepository;
use Illuminate\Support\Arr;

class ListSpiesQuery implements ListSpiesQueryContract
{
    private SpyRepository $repository;
    private int $perPage = 10;
    private array $filters = [];
    private array $sort = [];

    public function __construct(SpyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
    }

    public function setFilters(array $filters): self
    {
        $this->filters = $filters;
        return $this;
    }

    public function setSort(string $sort): self
    {
        $this->sort = $this->processSortFields($sort);
        return $this;
    }

    private function processSortFields(string $sort): array
    {
        $sortFields = explode(',', $sort);
        $result = [];

        foreach ($sortFields as $field) {
            $direction = 'asc';
            if (str_starts_with($field, '-')) {
                $direction = 'desc';
                $field = ltrim($field, '-');
            }

            // Validate supported sorting fields
            if (in_array($field, ['full_name', 'date_of_birth', 'date_of_death'])) {
                $result[$field] = $direction;
            } else {
                throw new \InvalidArgumentException("Unsupported sorting field: $field");
            }
        }

        return $result;
    }

    public function execute()
    {
        return $this->repository->paginate($this->perPage, Arr::except($this->filters, [null]), $this->sort);
    }
}
