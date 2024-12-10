<?php

namespace Jorro\Repository;

class Repository implements RepositoryInterface
{
    use RepositoryValuesTrait;

    /**
     * コンストラクタ
     *
     * @param array $values 初期設定値
     */
    public function __construct(protected array $values = [])
    {
    }
}
