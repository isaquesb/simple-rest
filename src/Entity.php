<?php
namespace Simple\Rest;

use Simple\Hydrate;

abstract class Entity extends Hydrate\Entity
{
    /**
     * @var string
     */
    protected $dateTimeFormat = 'Y-m-d H:i:s';

    /**
     * @param string|\DateTime $val
     * @param string $format
     * @return \DateTime|null
     */
    protected function asDateTimeVal($val, $format = null)
    {
        if (is_null($val)) {
            return null;
        }
        if (is_string($val)) {
            $asDate = strlen($val) <= 10;
            $dateFormat = $format ?: $this->dateTimeFormat;
            $join = strpos($dateFormat, 'T') ? 'T' : ' ';
            return \DateTime::createFromFormat($dateFormat, $asDate ? $val . $join . '00:00:00' : $val);
        }
        return $val;
    }

    /**
     * @param string|int $val
     * @return \DateTime|null
     */
    protected function asInteger($val)
    {
        if (is_null($val)) {
            return null;
        }
        if (is_string($val)) {
            return intval($val);
        }
        return $val;
    }

    /**
     * @param string|float $val
     * @return float|null
     */
    protected function asDecimal($val)
    {
        if (is_null($val)) {
            return null;
        }
        if (is_string($val)) {
            return floatval($val);
        }
        return $val;
    }

    /**
     * @param string|int|bool $val
     * @return bool|null
     */
    protected function asBool($val)
    {
        if (is_null($val)) {
            return null;
        }
        if (!is_bool($val)) {
            return boolval($val);
        }
        return $val;
    }

    /**
     * @param mixed $val
     * @return string
     */
    protected function asString($val)
    {
        if (is_null($val)) {
            return null;
        }
        return (string) $val;
    }
}
