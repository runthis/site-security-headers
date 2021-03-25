# Wordpress Site Security Headers

[![PHP Support](https://badgen.net/badge/php/%3E=7.3/blue?icon=github)](https://github.com/runthis/wordpress-security-headers/search?l=PHP)
[![Main Languages](https://img.shields.io/github/languages/top/runthis/wordpress-security-headers)](https://github.com/runthis/wordpress-security-headers/search?l=PHP)
[![Languages](https://img.shields.io/github/languages/count/runthis/wordpress-security-headers)](https://github.com/runthis/wordpress-security-headers/search?l=PHP)

[![GitHub last commit](https://img.shields.io/github/last-commit/runthis/wordpress-security-headers)](https://github.com/runthis/wordpress-security-headers/commits/master)

This is a WordPress plugin that checks your WordPress website for common headers associated with security. This plugin is opinionated and these things will not directly apply to every situation, so it is best to analyze individual applications to determine your own best path forward.

![image](https://user-images.githubusercontent.com/8216720/112517504-62e09c80-8d66-11eb-855c-1a7f66f360b2.png)


| Header name                | Expects |
| -------------------------- | ------- |
| strict-transport-security  | `max-age` >= 31536000 |
| referrer-policy            | `no-referrer` `no-referrer-when-downgrade` `origin` `origin-when-cross-origin` `same-origin` `strict-origin` `strict-origin-when-cross-origin` |
| permissions-policy         | Just expects this to exist in some way |
| content-security-policy    | Minimum of: `default-src 'none'` |
| x-frame-options            | `deny` `sameorigin` `allow-from` |
| x-content-type-options     | `nosniff` |
| x-xss-protection           | `0` `1` (informs you that it is deprecated and recommends CSP) |

