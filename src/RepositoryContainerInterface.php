<?php

namespace Jorro\Repository;


interface RepositoryContainerInterface extends ReadonlyRepositoryContainerInterface
{
    /**
     * 値の設定
     *
     * @param string $key キー
     * @param mixed $value 値
     * @return void
     */
    public function set(string $key, mixed $value): void;
}