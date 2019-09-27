<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Framework\Constraint;

use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class IsJsonTest extends ConstraintTestCase
{
    public static function evaluateDataprovider(): array
    {
        return [
            'valid JSON' => [true, '{}'],
            'empty string should be treated as invalid JSON' => [false, ''],
        ];
    }

    /**
     * @dataProvider evaluateDataprovider
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testEvaluate($expected, $jsonOther): void
    {
        $constraint = new IsJson;

        $this->assertEquals($expected, $constraint->evaluate($jsonOther, '', true));
    }
}