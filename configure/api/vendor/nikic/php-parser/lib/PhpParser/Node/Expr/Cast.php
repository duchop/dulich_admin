<?php

declare(strict_types = 1);
namespace PhpParser\Node\Expr;

use PhpParser\Node\Expr;

abstract class Cast extends Expr
{

    /** @var Expr Expression */
    public $expr;

    /**
     * Constructs a cast node.
     *
     * @param Expr $expr
     *            Expression
     * @param array $attributes
     *            Additional attributes
     */
    public function __construct(Expr $expr, array $attributes = [])
    {
        parent::__construct($attributes);
        $this->expr = $expr;
    }

    public function getSubNodeNames(): array
    {
        return [
            'expr'
        ];
    }
}