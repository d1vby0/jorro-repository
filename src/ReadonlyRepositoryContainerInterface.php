<?php

namespace Jorro\Repository;

use Jorro\Behavior\Encodable;

interface ReadonlyRepositoryContainerInterface extends Encodable
{
    /**
     * 指定したキーがあるか？
     *
     * @param string $key キー
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * 値の取得
     *
     * @param string $key キー
     * @param mixed $default キーが無いもしくは値がnullの時のデフォルト
     * @return mixed 値
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * キーを取得
     *
     * @return array
     */
    public function getKeys(): array;

    /**
     * キー・値を取得
     *
     * @return array
     */
    public function getValues(): array;
}