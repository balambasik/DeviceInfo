<?php

namespace Balambasik\DeviceInfo;

use Jaybizzle\CrawlerDetect\CrawlerDetect;

class DeviceInfo
{
    private $mobileDetect;
    private $crawlerDetect;
    private $sxGeo;
    private $asset;

    public $userAgent;
    public $ip;
    public $ipLong;
    public $deviceType;
    public $browser;
    public $os;
    public $geoCode;
    public $geoDetails;

    const ASSETS_URL = "ASSETS_URL";
    const ASSETS_BASE64 = "ASSETS_BASE64";
    const ASSETS_ALL = "ASSETS_ALL";

    public function __construct($userAgent = "", $ip = "")
    {
        $this->userAgent = $userAgent ? $userAgent : ($_SERVER["HTTP_USER_AGENT"] ?? "");
        $this->ip        = $ip ? $ip : $this->getRealIP();
        $this->ipLong    = $this->getIPLong();

        $this->mobileDetect = new \Mobile_Detect();
        $this->mobileDetect->setUserAgent($this->userAgent);
        $this->crawlerDetect = new CrawlerDetect();
        $this->asset         = new Asset();

        $sxGeoDbFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . "SxGeo.dat";

        if (!file_exists($sxGeoDbFile)) {
            throw new \Exception("SypexGeo database file - not exists!");
        }

        $this->sxGeo = new SxGeo($sxGeoDbFile);

        $this->deviceType = $this->getDeviceType();
        $this->browser    = $this->getBrowser();
        $this->os         = $this->getOS();
        $this->geoCode    = $this->getGeoCode();
        $this->geoDetails = (new GeoConverter($this->geoCode))->convert();
    }


    /**
     * @param $userAgent
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        $this->mobileDetect->setUserAgent($this->userAgent);

        // refresh
        $this->deviceType = $this->getDeviceType();
        $this->browser    = $this->getBrowser();
        $this->os         = $this->getOS();

        return $this;
    }

    /**
     * @param $ip
     * @return $this
     */
    public function setIP($ip)
    {
        $this->ip = $ip;

        // refresh
        $this->ipLong     = $this->getIPLong();
        $this->geoCode    = $this->getGeoCode();
        $this->geoDetails = (new GeoConverter($this->geoCode))->convert();

        return $this;
    }


    /**
     * @return string
     */
    private function getOS()
    {
        if ($this->deviceType === "tablet" || $this->deviceType === "mobile") {
            if ($this->mobileDetect->isIOS()) {
                return 'ios';
            } elseif ($this->mobileDetect->isAndroidOS()) {
                return 'android';
            } elseif ($this->mobileDetect->isSymbianOS()) {
                return 'symbian';
            } elseif ($this->mobileDetect->isBlackBerryOS()) {
                return 'black_berry';
            } elseif ($this->mobileDetect->isWindowsMobileOS()) {
                return 'windows_mobile';
            } elseif ($this->mobileDetect->isWindowsPhoneOS()) {
                return 'windows_phone';
            }

            return 'unknown_os';

        } else {
            if (preg_match('/windows nt 10/i', $this->userAgent)) {
                return 'windows_10';
            } elseif (preg_match('/windows nt 6\.3/i', $this->userAgent)) {
                return 'windows_8_1';
            } elseif (preg_match('/windows nt 6\.2/i', $this->userAgent)) {
                return 'windows_8';
            } elseif (preg_match('/windows nt 6\.1/i', $this->userAgent)) {
                return 'windows_7';
            } elseif (preg_match('/windows nt 5\.2/i', $this->userAgent)) {
                return 'windows_server';
            } elseif (preg_match('/windows nt 5\.1|windows xp/i', $this->userAgent)) {
                return 'windows_xp';
            } elseif (preg_match('/windows nt 5\.0/i', $this->userAgent)) {
                return 'windows_2000';
            } elseif (preg_match('/windows me/i', $this->userAgent)) {
                return 'windows_me';
            } elseif (preg_match('/macintosh|mac os x|mac_powerpc/i', $this->userAgent)) {
                return 'mac_os';
            } elseif (preg_match('/ubuntu/i', $this->userAgent)) {
                return 'ubuntu';
            } elseif (preg_match('/linux/i', $this->userAgent)) {
                return 'linux';
            } elseif (preg_match('/windows nt 6\.0/i', $this->userAgent)) {
                return 'windows_vista';
            }

            return 'unknown_os';
        }
    }

    /**
     * @return string
     */
    public function getDeviceType()
    {
        if ($this->crawlerDetect->isCrawler($this->userAgent)) {
            return "crawler";
        }

        if ($this->mobileDetect->isMobile()) {
            return "mobile";
        }

        if ($this->mobileDetect->isTablet()) {
            return "tablet";
        }

        return "desktop";
    }

    /**
     * @return string
     */
    private function getBrowser()
    {
        if ($this->deviceType === "crawler") {
            return 'unknown_browser';
        }

        if ($this->deviceType === "tablet" || $this->deviceType === "mobile") {
            if ($this->mobileDetect->isChrome()) {
                return 'chrome_mobile';
            } elseif ($this->mobileDetect->isOpera()) {
                return 'opera_mobile';
            } elseif ($this->mobileDetect->isDolfin()) {
                return 'dolphin_mobile';
            } elseif ($this->mobileDetect->isFirefox()) {
                return 'firefox_mobile';
            } elseif ($this->mobileDetect->isUCBrowser()) {
                return 'uc_browser_mobile';
            } elseif ($this->mobileDetect->isPuffin()) {
                return 'puffin_mobile';
            } elseif ($this->mobileDetect->isSafari()) {
                return 'safari_mobile';
            } elseif ($this->mobileDetect->isEdge()) {
                return 'edge_mobile';
            } elseif ($this->mobileDetect->isIE()) {
                return 'ie_mobile';
            } elseif (preg_match('/.*(Linux;.*AppleWebKit.*Version\/\d+\.\d+.*Mobile).*/i', $this->userAgent)) {
                return 'android_mobile';
            }

            return 'unknown_browser';

        } else {

            if (preg_match('/firefox/i', $this->userAgent)) {
                return 'firefox_desktop';
            } elseif (preg_match('/opr|opera/i', $this->userAgent)) {
                return 'opera_desktop';
            } elseif (preg_match('/edge/i', $this->userAgent)) {
                return 'edge_desktop';
            } elseif (preg_match('/chrome/i', $this->userAgent)) {
                return 'chrome_desktop';
            } elseif (preg_match('/maxthon/i', $this->userAgent)) {
                return 'maxthon_desktop';
            } elseif (preg_match('/safari/i', $this->userAgent)) {
                return 'safari_desktop';
            } elseif (preg_match('/msie|trident/i', $this->userAgent)) {
                return 'ie_desktop';
            }

            return 'unknown_browser';
        }
    }

    /**
     * @return mixed|string
     */
    private function getGeoCode()
    {
        return $this->sxGeo->getCountry($this->ip) ?: "XX";
    }

    /**
     * @return int
     */
    private function getIPLong()
    {
        return intval(sprintf("%u", ip2long($this->ip)));
    }

    /**
     * @return string
     */
    private function getRealIP()
    {
        $raws = [
            isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : '',
            isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : '',
            isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '',
            isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : ''
        ];

        foreach ($raws as $rawIP) {
            $ipList = explode(',', $rawIP);
            $ip     = trim(end($ipList));

            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        return '0.0.0.0';
    }

    /**
     * @param null $useAssets
     * @return array
     */
    public function getInfo($useAssets = null)
    {
        $out = [
            "device_type" => $this->deviceType,
            "os"          => $this->os,
            "browser"     => $this->browser,
            "geo_code"    => $this->geoCode,
            "ip"          => $this->ip,
            "long_ip"     => $this->ipLong,
            "geo_details" => $this->geoDetails,
        ];

        if ($useAssets === self::ASSETS_ALL) {
            $out["assets"] = [
                "url"    => [
                    "device_type" => $this->asset->get(Asset::DEVICE_TYPE, $this->deviceType)->url(),
                    "os"          => $this->asset->get(Asset::OS, $this->os)->url(),
                    "browser"     => $this->asset->get(Asset::BROWSER, $this->browser)->url(),
                    "geo"         => $this->asset->get(Asset::GEO, $this->geoCode)->url(),],
                "base64" => [
                    "device_type" => $this->asset->get(Asset::DEVICE_TYPE, $this->deviceType)->base64(),
                    "os"          => $this->asset->get(Asset::OS, $this->os)->base64(),
                    "browser"     => $this->asset->get(Asset::BROWSER, $this->browser)->base64(),
                    "geo"         => $this->asset->get(Asset::GEO, $this->geoCode)->base64(),
                ]
            ];

            return $out;
        }

        if ($useAssets === self::ASSETS_URL) {
            $out["assets"] = [
                "device_type" => $this->asset->get(Asset::DEVICE_TYPE, $this->deviceType)->url(),
                "os"          => $this->asset->get(Asset::OS, $this->os)->url(),
                "browser"     => $this->asset->get(Asset::BROWSER, $this->browser)->url(),
                "geo"         => $this->asset->get(Asset::GEO, $this->geoCode)->url(),
            ];

            return $out;
        }

        if ($useAssets === self::ASSETS_BASE64) {
            $out["assets"] = [
                "device_type" => $this->asset->get(Asset::DEVICE_TYPE, $this->deviceType)->base64(),
                "os"          => $this->asset->get(Asset::OS, $this->os)->base64(),
                "browser"     => $this->asset->get(Asset::BROWSER, $this->browser)->base64(),
                "geo"         => $this->asset->get(Asset::GEO, $this->geoCode)->base64(),
            ];

            return $out;
        }

        return $out;
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function list($key = null)
    {
        $list = [
            "device_types"    => [
                "crawler",
                "mobile",
                "tablet",
                "desktop",
            ],
            "os"              => [
                'ios',
                'android',
                'symbian',
                'black_berry',
                'windows_mobile',
                'windows_phone',
                'windows_10',
                'windows_8_1',
                'windows_8',
                'windows_7',
                'windows_server',
                'windows_xp',
                'windows_2000',
                'windows_me',
                'mac_os',
                'ubuntu',
                'linux',
                'windows_vista',
                'unknown_os',
            ],
            "browsers"        => [
                'chrome_mobile',
                'opera_mobile',
                'dolphin_mobile',
                'firefox_mobile',
                'uc_browser_mobile',
                'puffin_mobile',
                'safari_mobile',
                'edge_mobile',
                'ie_mobile',
                'android_mobile',
                'firefox_desktop',
                'opera_desktop',
                'edge_desktop',
                'chrome_desktop',
                'maxthon_desktop',
                'safari_desktop',
                'ie_desktop',
                'unknown_browser',
            ],
            "countries_codes" => GeoConverter::getAlfa2List(),
            "countries_names" => GeoConverter::getCountryNamesList(),
        ];

        return isset($list[$key]) ? $list[$key] : $list;

    }
}