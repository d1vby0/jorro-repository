<?php

namespace Jorro\Repository;

use Jorro\Repository\Repository;
use Jorro\Behavior\Arrayable;
use Jorro\Repository\RepositoryContainer;

trait RepositoryContainerTrait
{
    use ReadonlyRepositoryContainerTrait;

    /**
     *
     *
     * 値の設定
     *
     * @param string $key キー
     * @param mixed $value 値
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->repository->set($key, $value);
    }
}
