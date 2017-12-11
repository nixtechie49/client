<?php
/**
 * This file is part of the IOTA PHP package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Techworker\IOTA\Util;

use Techworker\IOTA\Exception;
use Techworker\IOTA\Type\Trytes;

class TrytesUtil
{
    /**
     * Small helper function that will transform the given trytes as string
     * to a Trytes bases instance of your choice specified by the $cls param.
     *
     * @param $trytes
     * @param string|null $cls
     * @return Trytes
     */
    public static function stringToTrytes($trytes, string $cls = null): Trytes
    {
        if ($trytes instanceof Trytes) {
            return $trytes;
        }
        if (null === $cls) {
            return new Trytes($trytes);
        }

        return new $cls($trytes);
    }

    /**
     * @param array $trytesArray
     * @param string|null $cls
     * @return array
     */
    public static function arrayToTrytes(array $trytesArray, string $cls = null): array
    {
        $result = [];
        foreach ($trytesArray as $trytes) {
            $result[] = self::stringToTrytes($trytes, $cls);
        }

        return $result;
    }

    /**
     * Converts a text to trytes.
     *
     * @param string $input
     * @return null|string
     */
    public static function asciiToTrytes(string $input): ?string
    {
        $TRYTE_VALUES = '9ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $trytes = '';

        $length = \strlen($input);
        for ($i = 0; $i < $length; ++$i) {
            $char = $input[$i];
            $asciiValue = \ord($char);

            // If not recognizable ASCII character, return null
            if ($asciiValue > 255) {
                //asciiValue = 32
                return null;
            }

            $firstValue = $asciiValue % 27;
            $secondValue = ($asciiValue - $firstValue) / 27;

            $trytesValue = $TRYTE_VALUES[$firstValue].$TRYTE_VALUES[$secondValue];

            $trytes .= $trytesValue;
        }

        return $trytes;
    }

    /**
     * Converts trytes to text.
     *
     * @param Trytes $inputTrytes
     * @return string
     * @throws \Exception
     */
    public static function asciiFromTrytes(Trytes $inputTrytes): string
    {
        // If input length is odd, return null
        if (0 === $inputTrytes->count() % 2) {
            // TODO: we can do better than that
            throw new Exception('not even.');
        }

        $tryteValues = '9ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $trytesString = (string) $inputTrytes;
        $result = '';
        for ($i = 0; $i < $inputTrytes->count(); $i += 2) {
            $firstValue = strpos($tryteValues, $trytesString[$i] ?? 'ÄH');
            $secondValue = strpos($tryteValues, $trytesString[$i + 1] ?? 'ÄH');
            $decimalValue = $firstValue + $secondValue * 27;
            $result .= \chr($decimalValue);
        }

        return $result;
    }

    /**
     * Returns null hash trytes.
     *
     * @return Trytes
     */
    public static function nullHashTrytes(): Trytes
    {
        return new Trytes(str_repeat('9', 243));
    }

    /**
     * Converts the given trytes to trits.
     *
     * @param Trytes $trytes
     * @return array
     */
    public static function toTrits(Trytes $trytes) : array
    {
        $trits = [];

        foreach ($trytes as $tryte) {
            foreach (TryteUtil::toTrits($tryte) as $trit) {
                $trits[] = $trit;
            }
        }

        return $trits;
    }
}