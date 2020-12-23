<?php

namespace AXM\TD;

class JobResult
{
    const FORMAT_JSON = 'json';
    const FORMAT_MSGPACK = 'msgpack';
    const FORMAT_MSGPACK_GZ = 'msgpack.gz';

    /**
     * @var string
     */
    public $format;

    /**
     * JobResult constructor.
     *
     * @param string $format
     * @throws \InvalidArgumentException
     */
    public function __construct(string $format)
    {
        $formats = [static::FORMAT_JSON, static::FORMAT_MSGPACK, static::FORMAT_MSGPACK_GZ];
        if (!in_array($format, $formats, true)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid format argument. Accepts : %s', implode(',', $formats))
            );
        }

        if (in_array($format, [static::FORMAT_MSGPACK, static::FORMAT_MSGPACK_GZ], true)) {
            if (!extension_loaded("msgpack")) {
                throw new \InvalidArgumentException('msgpack extension should be installed');
            }
        }

        if ($format === static::FORMAT_MSGPACK_GZ) {
            if (!extension_loaded("zlib")) {
                throw new \InvalidArgumentException('zlib extension should be installed');
            }
        }

        $this->format = $format;
    }

    /**
     * convert to array
     *
     * @param string $contents
     * @return array
     */
    public function toArray(string $contents): array
    {
        if ($this->format === static::FORMAT_JSON) {
            return $this->jsonlDecode($contents);
        }

        if ($this->format === static::FORMAT_MSGPACK) {
            return $this->unpack($contents);
        }

        if ($this->format === static::FORMAT_MSGPACK_GZ) {
            return $this->unpack(gzdecode($contents));
        }

        return [];
    }

    /**
     * decode jsonl(JSON Lines)
     *
     * @param string $contents
     * @return array
     */
    protected function jsonlDecode(string $contents): array
    {
        $result = [];
        $contents = explode("\n", trim($contents));
        foreach ($contents as $content) {
            $result[] = json_decode($content, true);
        }
        return $result;
    }

    /**
     * unpack contents
     *
     * @param string $contents
     * @return array
     */
    protected function unpack(string $contents): array
    {
        $result = [];
        $unpacker = new \MessagePackUnpacker(false);
        $unpacker->feed($contents);
        while ($unpacker->execute()) {
            $result[] = $unpacker->data();
        }
        return $result;
    }
}
