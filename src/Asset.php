<?php


namespace Balambasik\DeviceInfo;


class Asset
{
    const DEVICE_TYPE = "types";
    const OS = "os";
    const GEO = "flags";
    const BROWSER = "browsers";

    private $path = "";

    /**
     * @return string
     */
    public function url()
    {
        $protocol = $this->isSSL() ? "https" : "http";

        return $protocol . "://" . $_SERVER['HTTP_HOST'] . str_replace(
                $this->urlSeparator($_SERVER['DOCUMENT_ROOT']), '', $this->urlSeparator($this->path));
    }

    /**
     * @return string
     */
    public function base64()
    {
        return base64_encode($this->path);
    }

    /**
     * @param $dir
     * @param $name
     * @return $this
     */
    public function get($dir, $name)
    {
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR
            . $dir . DIRECTORY_SEPARATOR . $name . ".png";

        if (file_exists($path)) {
            $this->path = $path;
        } else {
            $this->path = dirname(__FILE__) . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR
                . "os" . DIRECTORY_SEPARATOR . "unknown_os.png";
        }

        return $this;
    }

    /**
     * @param $str
     * @return string|string[]
     */
    private function urlSeparator($str)
    {
        return str_replace('\\', "/", $str);
    }

    /**
     * @return bool
     */
    private function isSSL()
    {
        if (isset($_SERVER['HTTPS'])) {
            if ('on' == strtolower($_SERVER['HTTPS']))
                return true;
            if ('1' == $_SERVER['HTTPS'])
                return true;
        } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
            return true;
        }
        return false;
    }
}