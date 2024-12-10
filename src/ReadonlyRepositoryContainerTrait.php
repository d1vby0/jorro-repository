<?php

namespace Jorro\Repository;

use Jorro\Repository\Repository;
use Jorro\Behavior\Arrayable;

trait ReadonlyRepositoryContainerTrait
{
    protected Repository $repository;

    /**
     * 指定したキーがあるか？
     *
     * @param string $key キー
     * @return bool
     */
    public function has(string $key): bool
    {
        return $this->repository->has($key);
    }

    /**
     * 値の取得
     *
     * @param string $key キー
     * @param mixed $default キーが無いもしくは値がnullの時のデフォルト
     * @return mixed 値
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->repository->get($key, $default);
    }

    /**
     * キーを取得
     *
     * @return array
     */
    public function getKeys(): array
    {
        return $this->repository->getKeys();
    }

    /**
     * キー・値を取得
     *
     * @return array
     */
    public function getValues(): array
    {
        return $this->repository->getValues();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->repository->toArray();
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return $this->repository->toJson();
    }
}
