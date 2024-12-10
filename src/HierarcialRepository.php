<?php

namespace Jorro\Repository;

use Jorro\Behavior\Arrayable;

class HierarcialRepository implements HierarcialRepositoryInterface
{
    use RepositoryValuesTrait;

    /**
     * コンストラクタ
     *
     * @param array $values 初期設定値
     * @param string $separator キーセパレータ
     */
    public function __construct(protected array $values = [], protected string $separator = '.')
    {
        $this->expandValues();
    }

    /**
     * キーに対応するノードの取得
     *
     * @param string|null $key キー (nullの場合はルートノード)
     * @param bool $digIfNotExists 対応するノードがない場合作成するか？
     * @param mixed $exists 対応するノードが存在するかを戻す (true === 存在する)
     * @return mixed ノード
     */
    protected function &getNode(?string $key = null, bool $digIfNotExists = true, &$exists = true): mixed
    {
        $exists = true;
        if (is_null($key)) {
            return $this->values;
        }
        $keys = explode($this->separator, $key);
        $node = &$this->values;
        foreach ($keys as $currentKey) {
            if (!isset($node[$currentKey])) {
                if (!$digIfNotExists) {
                    $exists = null;

                    return $exists;
                }
                if (!is_array($node)) {
                    $node = [$currentKey => []];
                } else {
                    $node[$currentKey] = [];
                }
            }
            $node = &$node[$currentKey];
        }

        return $node;
    }

    /**
     * 指定したキーがあるか？
     *
     * @param string $key キー
     * @return bool
     */
    public function has(string $key): bool
    {
        $this->getNode($key, false, $exists);

        return !is_null($exists);
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
        return $this->getNode($key, false) ?? $default;
    }

    /**
     * 区切り文字付配列の展開
     *
     * @return void
     */
    protected function expandValues(): void
    {
        if (!empty($this->values)) {
            foreach ($this->values as $key => $value) {
                if (str_contains($key, $this->separator)) {
                    $this->set($key, $value);
                    unset($this->values[$key]);
                }
            }
        }
    }

    /**
     * キーを取得
     * 下位階層キーは $this->separator 区切りとなる
     *
     * @param string|null $offsetKey 取得位置の始点キー
     * @param bool $trimRootKey 始点キー以前の上位キー文字列をトリムするか？
     * @param int $limitDepth 取得する深さ
     * @return array
     */
    public function getKeys(?string $offsetKey = null, bool $trimRootKey = true, int $limitDepth = 0): array
    {
        $node = $this->getNode($offsetKey, false, $exists);
        if (!$exists) {
            return [];
        }
        $keys = [];
        if (is_array($node)) {
            $limitDepth = ($limitDepth) ? $limitDepth : -1;
            $this->getKeysRecursive($node, ($trimRootKey) ? '' : (($offsetKey) ? $offsetKey . $this->separator : ''), $keys, $limitDepth);
        }

        return $keys;
    }

    /**
     * キー取得再帰呼び出し用ロジック
     *
     * @param array $node 現在のノード
     * @param string $prefix 付与するキープレフィックス
     * @param array $keys 結果
     * @param int $depth 深さ (0 = 終了)
     * @return void
     */
    protected function getKeysRecursive(array $node, string $prefix, array &$keys, int $depth): void
    {
        if (!$depth--) {
            return;
        }
        foreach ($node as $key => $value) {
            if (!is_numeric($key)) {
                $keys[] = $prefix . $key;
            }
            if (is_array($value)) {
                $this->getKeysRecursive($value, $prefix . $key . '.', $keys, $depth);
            }
        }
    }

    /**
     * キー・値を取得
     *
     * @param string|null $offsetKey 取得位置の始点キー
     * @param bool $trimRootKey 始点キー以前の上位キー文字列をトリムするか？
     * @param bool $alsoNumericKey 数値キーについてもキー文字列化を行うか？
     * @param int $limitDepth 取得する深さ
     * @return array
     */
    public function getValues(?string $offsetKey = null, bool $trimRootKey = true, bool $alsoNumericKey = false, int $limitDepth = 0): array
    {
        $node = $this->getNode($offsetKey, false, $exists);
        if (!$exists) {
            return [];
        }
        $values = [];
        if (is_array($node)) {
            $limitDepth = ($limitDepth) ? $limitDepth : -1;
            $this->getValuesRecursive($node, ($trimRootKey) ? '' : (($offsetKey) ? $offsetKey . $this->separator : ''), $values, $alsoNumericKey, $limitDepth, false);
        }

        return $values;
    }

    /**
     * キー・値取得再帰呼び出し用ロジック
     *
     * @param array $node 現在のノード
     * @param string $prefix 付与するキープレフィックス
     * @param array $values 結果
     * @param bool $alsoNumericKey 数値キーについてもキー文字列化を行うか？
     * @param int $depth $depth 深さ (0 = 終了)
     * @param bool $abortAtNumericKey 数値キーの場合の中断
     * @return bool
     */
    protected function getValuesRecursive(array $node, string $prefix, array &$values, bool $alsoNumericKey, int $depth, bool $abortAtNumericKey): bool
    {
        if (!$depth--) {
            return false;
        }
        $values = [];
        foreach ($node as $key => $value) {
            if ((!$alsoNumericKey) && (is_numeric($key))) {
                if ($abortAtNumericKey) {
                    return false;
                } else {
                    $values[] = $value;
                }
                continue;
            }
            if (!is_array($value)) {
                $values[$prefix . $key] = $value;
                continue;
            }
            $childNodeValues = [];
            if ($this->getValuesRecursive($value, $prefix . $key . $this->separator, $childNodeValues, $alsoNumericKey, $depth, true)) {
                $values = array_merge($values, $childNodeValues);
            } else {
                $values[$prefix . $key] = $value;
            }
        }

        return true;
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
            $node = &$this->getNode($key);
            $node = $value;
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
        $keys = explode($this->separator, $key);
        $unsetKey = array_pop($keys);
        $node = &$this->getNode(implode($this->separator, $keys) ?: null, false, $exists);
        if (($exists) && (is_array($node))) {
            unset($node[$unsetKey]);
        }
    }

    /**
     * マージ
     *
     * @param array|self $repository マージする値
     * @param bool $override $valuesで上書きか？
     * @param bool $recursive 再帰マージを行うか？
     * @param string|null $offsetKey 始点キー
     * @return void
     */
    public function merge(array|Arrayable $repository, bool $override = true, bool $recursive = true, ?string $offsetKey = null): void
    {
        $node = &$this->getNode($offsetKey);
        if (is_array($repository)) {
            $repository = new static($repository, $this->separator);
        }
        $repository = $repository->toArray();
        if ($recursive) {
            if ($override) {
                $node = array_merge_recursive($node, $repository);
            } else {
                $node = array_merge_recursive($repository, $node);
            }
        } else {
            if ($override) {
                $node = array_merge($node, $repository);
            } else {
                $node = array_merge($repository, $node);
            }
        }
    }

    /**
     * 置換
     *
     * @param array|self $values 置換する値
     * @param bool $override $valuesで上書きか？
     * @param bool $recursive 再帰置換を行うか？
     * @param string|null $offsetKey 始点キー
     * @return void
     */
    public function replace(array|Arrayable $repository, bool $override = true, bool $recursive = true, string $offsetKey = null): void
    {
        $this->getNode($offsetKey, $node);
        if (is_array($repository)) {
            $repository = new static($repository, $this->separator);
        }
        $repository = $repository->toArray();
        if ($recursive) {
            if ($override) {
                $node = array_replace_recursive($node, $repository);
            } else {
                $node = array_replace_recursive($repository, $node);
            }
        } else {
            if ($override) {
                $node = array_replace($node, $repository);
            } else {
                $node = array_replace($repository, $node);
            }
        }
    }
}
