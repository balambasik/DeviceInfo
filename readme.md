## Device Info
A lightweight library for defining basic device data.
Detection will be performed based on UserAgent and IP address.
Allows you to determine: device type, operating system, browser, country.


## Install

`composer require balambasik/deviceinfo`

## Usage 
Getting information about the current device

```php 
<?php

include "vendor/autoload.php";

use Balambasik\DeviceInfo\DeviceInfo;

$devInfo = new DeviceInfo();

print_r($devInfo->getInfo());

```

**Result**

```
Array
(
    [device_type] => desktop
    [os] => windows_10
    [browser] => chrome_desktop
    [geo_code] => US
    [ip] => 8.8.8.8
    [long_ip] => 134744072
    [geo_details] => Array
        (
            [name] => United States of America
            [alfa2] => US
            [alfa3] => USA
            [numeric] => 840
        )
)

```

**Assets**

You can also get icons.
(National flag, device type, OS, browser)

```php 
<?php

include "vendor/autoload.php";

use Balambasik\DeviceInfo\DeviceInfo;

$devInfo = new DeviceInfo();

print_r($devInfo->getInfo(DeviceInfo::ASSETS_BASE64)); // icons base64
// print_r($devInfo->getInfo(DeviceInfo::ASSETS_URL)); // icons URLs
// print_r($devInfo->getInfo(DeviceInfo::ASSETS_ALL)); URLs and base64

```


**Result**

```
Array
(
    [device_type] => desktop
    [os] => windows_10
    [browser] => chrome_desktop
    [geo_code] => US
    [ip] => 8.8.8.8
    [long_ip] => 134744072
    [geo_details] => Array
        (
            [name] => United States of America
            [alfa2] => US
            [alfa3] => USA
            [numeric] => 840
        )
    [assets] => Array
        (
            [device_type] => QzpcT1NQYW5lbFxkb21haW5zXHJlcXVlc3RpbmZvXHNyY1xhc3NldHNcdHlwZXNcZGVza3RvcC5wbmc=
            [os] => QzpcT1NQYW5lbFxkb21haW5zXHJlcXVlc3RpbmZvXHNyY1xhc3NldHNcb3Ncd2luZG93c18xMC5wbmc=
            [browser] => QzpcT1NQYW5lbFxkb21haW5zXHJlcXVlc3RpbmZvXHNyY1xhc3NldHNcYnJvd3NlcnNcY2hyb21lX2Rlc2t0b3AucG5n
            [geo] => QzpcT1NQYW5lbFxkb21haW5zXHJlcXVlc3RpbmZvXHNyY1xhc3NldHNcZmxhZ3NcVVMucG5n
        )
)
```

## Custom IP or UserAgent

**Use constructor**

```php
use Balambasik\DeviceInfo\DeviceInfo;

$devInfo = new DeviceInfo("Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0", "4.4.4.4");

print_r($devInfo->getInfo(DeviceInfo::ASSETS_BASE64));

```



**Use setters**
```php
<?php

include "vendor/autoload.php";

use Balambasik\DeviceInfo\DeviceInfo;

$devInfo = new DeviceInfo();

$info = $devInfo
    ->setIP("4.4.4.4")
    ->setUserAgent("Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0")
    ->getInfo(DeviceInfo::ASSETS_BASE64);

print_r($info);

```

**Result**

```
Array
(
    [device_type] => desktop
    [os] => windows_7
    [browser] => firefox_desktop
    [geo_code] => US
    [ip] => 4.4.4.4
    [long_ip] => 67372036
    [geo_details] => Array
        (
            [name] => United States of America
            [alfa2] => US
            [alfa3] => USA
            [numeric] => 840
        )

    [assets] => Array
        (
            [device_type] => QzpcT1NQYW5lbFxkb21haW5zXHJlcXVlc3RpbmZvXHNyY1xhc3NldHNcdHlwZXNcZGVza3RvcC5wbmc=
            [os] => QzpcT1NQYW5lbFxkb21haW5zXHJlcXVlc3RpbmZvXHNyY1xhc3NldHNcb3Ncd2luZG93c183LnBuZw==
            [browser] => QzpcT1NQYW5lbFxkb21haW5zXHJlcXVlc3RpbmZvXHNyY1xhc3NldHNcYnJvd3NlcnNcZmlyZWZveF9kZXNrdG9wLnBuZw==
            [geo] => QzpcT1NQYW5lbFxkb21haW5zXHJlcXVlc3RpbmZvXHNyY1xhc3NldHNcZmxhZ3NcVVMucG5n
        )

)

```


## Values list

```php
<?php

include "vendor/autoload.php";

use Balambasik\DeviceInfo\DeviceInfo;

print_r(DeviceInfo::list());

```


**Result**

```
Array
(
    [device_types] => Array
        (
            [0] => crawler
            [1] => mobile
            [2] => tablet
            [3] => desktop
        )
    [os] => Array
        (
            [0] => ios
            [1] => android
            [2] => symbian
            [3] => black_berry
            [4] => windows_mobile
            [5] => windows_phone
            [6] => windows_10
            [7] => windows_8_1
            [8] => windows_8
            [9] => windows_7
            [10] => windows_server
            [11] => windows_xp
            [12] => windows_2000
            [13] => windows_me
            [14] => mac_os
            [15] => ubuntu
            [16] => linux
            [17] => windows_vista
            [18] => unknown_os
        )
    [browsers] => Array
        (
            [0] => chrome_mobile
            [1] => opera_mobile
            [2] => dolphin_mobile
            [3] => firefox_mobile
            [4] => uc_browser_mobile
            [5] => puffin_mobile
            [6] => safari_mobile
            [7] => edge_mobile
            [8] => ie_mobile
            [9] => android_mobile
            [10] => firefox_desktop
            [11] => opera_desktop
            [12] => edge_desktop
            [13] => chrome_desktop
            [14] => maxthon_desktop
            [15] => safari_desktop
            [16] => ie_desktop
            [17] => unknown_browser
        )
    [countries_codes] => Array
        (
            [0] => XX
            ...
            [249] => ...
        )
    [countries_names] => Array
        (
            [0] => Unknown country
            ...
            [249] => ...
        )
)

```


## Dependencies
<https://github.com/JayBizzle/Crawler-Detect>

<https://github.com/serbanghita/Mobile-Detect>

<https://sypexgeo.net/en/>
