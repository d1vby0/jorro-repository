<?php

namespace Jorro\Repository;

use Jorro\Behavior\Arrayable;
use Jorro\Behavior\Clearable;
use Jorro\Behavior\Decodable;
use Jorro\Behavior\Encodable;

interface RepositoryInterface extends Clearable, Encodable, Decodable
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
     * 配列をアタッチ
     *
     * @param array $values 配列
     * @return void
     */
    public function attach(array &$values): void;

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

    /**
     * 値の設定
     *
     * @param string $key キー
     * @param mixed $value 値
     * @return void
     */
    public function set(string $key, mixed $value): void;

    /**
     * キーの削除
     *
     * @param string $key
     * @return void
     */
    public function unset(string $key): void;

    /**
     * @inheritDoc
     */
    public function clear(): void;

    /**
     * マージ
     *
     * @param array|\Jorro\Behavior\Arrayable $values マージする値
     * @param bool $override $valuesで上書きか？
     * @return void
     */
    public function merge(array|Arrayable $values, bool $override = true): void;

    /**
     * 置換
     *
     * @param array|\Jorro\Behavior\Arrayable $values 置換する値
     * @param bool $override $valuesで上書きか？
     * @return void
     */
    public function replace(array|Arrayable $values, bool $override = true): void;
}