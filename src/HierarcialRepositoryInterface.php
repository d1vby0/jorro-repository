<?php

namespace Jorro\Repository;

use Jorro\Behavior\Arrayable;

interface HierarcialRepositoryInterface extends RepositoryInterface
{
    /**
     * マージ
     *
     * @param array|self $repository マージする値
     * @param bool $override $valuesで上書きか？
     * @param bool $recursive 再帰マージを行うか？
     * @param string|null $offsetKey 始点キー
     * @return void
     */
    public function merge(array|Arrayable $values, bool $override = true,  bool $recursive = true, ?string $offsetKey = null): void;

    /**
     * 置換
     *
     * @param array|self $values 置換する値
     * @param bool $override $valuesで上書きか？
     * @param bool $recursive 再帰置換を行うか？
     * @param string|null $offsetKey 始点キー
     * @return void
     */
    public function replace(array|Arrayable $repository, bool $override = true,  bool $recursive = true, ?string $offsetKey = null): void;
}