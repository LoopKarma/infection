<?php

declare(strict_types=1);

namespace Infection\Mutator\ReturnValue;

use Infection\Mutator\Mutator;
use PhpParser\Node;

class IntegerNegotiation implements Mutator
{
    public function mutate(Node $node) {
        $integerValue = $node->expr instanceof Node\Expr\UnaryMinus
            ? -$node->expr->expr->value
            : $node->expr->value;

        return new Node\Stmt\Return_(
            new Node\Scalar\LNumber(-1 * $integerValue, $node->getAttributes())
        );
    }

    public function shouldMutate(Node $node): bool
    {
        if (! $node instanceof Node\Stmt\Return_) {
            return false;
        }

        $expr = $node->expr;

        if ($expr instanceof Node\Expr\UnaryMinus) {
            $expr = $node->expr->expr;
        }

        if (! $expr instanceof Node\Scalar\LNumber) {
            return false;
        }

        if ($expr->value === 0) {
            return false;
        };

        return true;
    }
}