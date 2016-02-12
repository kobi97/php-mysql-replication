<?php

/**
 * Class BinLogEvent
 */
class BinLogEvent
{
    /**
     * @var string
     */
    public static $EVENT_TYPE;
    /**
     * @var int
     */
    public static $TABLE_ID;
    /**
     * @var string
     */
    public static $TABLE_NAME;
    /**
     * @var string
     */
    public static $SCHEMA_NAME;
    /**
     * @var []
     */
    public static $TABLE_MAP;
    /**
     * @var BinLogPack
     */
    public static $PACK;
    /**
     * @var int
     */
    public static $PACK_SIZE;
    /**
     * @var string
     */
    public static $FLAGS;
    /**
     * @var int
     */
    public static $EXTRA_DATA_LENGTH;
    /**
     * @var string
     */
    public static $EXTRA_DATA;
    /**
     * @var int
     */
    public static $SCHEMA_LENGTH;
    /**
     * @var int
     */
    public static $COLUMNS_NUM;
    /**
     * @var array
     */
    public static $bitCountInByte = [
        0, 1, 1, 2, 1, 2, 2, 3, 1, 2, 2, 3, 2, 3, 3, 4,
        1, 2, 2, 3, 2, 3, 3, 4, 2, 3, 3, 4, 3, 4, 4, 5,
        1, 2, 2, 3, 2, 3, 3, 4, 2, 3, 3, 4, 3, 4, 4, 5,
        2, 3, 3, 4, 3, 4, 4, 5, 3, 4, 4, 5, 4, 5, 5, 6,
        1, 2, 2, 3, 2, 3, 3, 4, 2, 3, 3, 4, 3, 4, 4, 5,
        2, 3, 3, 4, 3, 4, 4, 5, 3, 4, 4, 5, 4, 5, 5, 6,
        2, 3, 3, 4, 3, 4, 4, 5, 3, 4, 4, 5, 4, 5, 5, 6,
        3, 4, 4, 5, 4, 5, 5, 6, 4, 5, 5, 6, 5, 6, 6, 7,
        1, 2, 2, 3, 2, 3, 3, 4, 2, 3, 3, 4, 3, 4, 4, 5,
        2, 3, 3, 4, 3, 4, 4, 5, 3, 4, 4, 5, 4, 5, 5, 6,
        2, 3, 3, 4, 3, 4, 4, 5, 3, 4, 4, 5, 4, 5, 5, 6,
        3, 4, 4, 5, 4, 5, 5, 6, 4, 5, 5, 6, 5, 6, 6, 7,
        2, 3, 3, 4, 3, 4, 4, 5, 3, 4, 4, 5, 4, 5, 5, 6,
        3, 4, 4, 5, 4, 5, 5, 6, 4, 5, 5, 6, 5, 6, 6, 7,
        3, 4, 4, 5, 4, 5, 5, 6, 4, 5, 5, 6, 5, 6, 6, 7,
        4, 5, 5, 6, 5, 6, 6, 7, 5, 6, 6, 7, 6, 7, 7, 8,
    ];

    /**
     * @param BinLogPack $pack
     * @param string $event_type
     * @param int $size
     */
    public static function _init(BinLogPack $pack, $event_type, $size = 0)
    {
        self::$PACK = $pack;
        self::$EVENT_TYPE = $event_type;
        self::$PACK_SIZE = $size;
    }

    /**
     * @return int
     */
    public static function readTableId()
    {
        $a = (int)(ord(self::$PACK->read(1)) & 0xFF);
        $a += (int)((ord(self::$PACK->read(1)) & 0xFF) << 8);
        $a += (int)((ord(self::$PACK->read(1)) & 0xFF) << 16);
        $a += (int)((ord(self::$PACK->read(1)) & 0xFF) << 24);
        $a += (int)((ord(self::$PACK->read(1)) & 0xFF) << 32);
        $a += (int)((ord(self::$PACK->read(1)) & 0xFF) << 40);

        return $a;
    }

    /**
     * @param string $bitmap
     * @return int
     */
    public static function bitCount($bitmap)
    {
        $n = 0;
        for ($i = 0; $i < strlen($bitmap); $i++)
        {
            $bit = $bitmap[$i];
            if (is_string($bit))
            {
                $bit = ord($bit);
            }
            $n += self::$bitCountInByte[$bit];
        }

        return $n;
    }
}