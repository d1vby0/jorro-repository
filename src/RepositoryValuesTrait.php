<?php

namespace Jorro\Repository;

use Jorro\Behavior\Arrayable;

/**
 * @property array $values
 */
trait RepositoryValuesTrait
{
    protected array $values;

    /**
     * 指定したキーがあるか？
     *
     * @param string $key キー
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->values);
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
        return $this->values[$key] ?? $default;
    }

    /**
     * キーを取得
     *
     * @return array
     */
    public function getKeys(): array
    {
        return array_keys($this->values);
    }

    /**
     * キー・値を取得
     *
     * @return array
     */
    public function getValues(): array
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->values;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->values, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 配列をアタッチ
     *
     * @param array $values 配列
     * @return void
     */
    public function attach(array &$values): void
    {
        $this->values = &$values;
    }

    /**
     * 配列から設定
     *
     * @return void
     */
    public function fromArray(array $values): void
    {
        $this->values = $values;
    }

    /**
     * Jsonから設定
     * @param string $json
     * @return void
     */
    public function fromJson(string $json): void
    {
        $this->values = json_decode($json, true);
    }

    /**
     * 値の設定
     *
     * @param string $key キー
     * @param mixed $value 値
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        if (is_null($value)) {
            $this->unset($key);
        } else {
            $this->values[$key] = $value;
        }
    }

    /**
     * キーの削除
     *
     * @param string $key
     * @return void
     */
    public function unset(string $key): void
    {
        unset ($this->values[$key]);
    }

    /**
     * @inheritDoc
     */
    public function clear(): void
    {
        $this->values = [];
    }

    /**
     * マージ
     *
     * @param array|\Jorro\Behavior\Arrayable $values マージする値
     * @param bool $override $valuesで上書きか？
     * @return void
     */
    public function merge(array|Arrayable $values, bool $override = true): void
    {
        if (!is_array($values)) {
            $repository = $values->toArray();
        }
        if ($override) {
            $this->values = array_merge($this->values, $values);
        } else {
            $this->values = array_merge($values, $this->values);
        }
    }

    /**
     * 置換
     *
     * @param array|\Jorro\Behavior\Arrayable $values 置換する値
     * @param bool $override $valuesで上書きか？
     * @return void
     */
    public function replace(array|Arrayable $values, bool $override = true): void
    {
        if (!is_array($values)) {
            $values = $values->toArray();
        }
        if ($override) {
            $this->values = array_replace($this->values, $values);
        } else {
            $this->values = array_replace($values, $this->values);
        }
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool
    {
        return empty($this->values);
    }
}
