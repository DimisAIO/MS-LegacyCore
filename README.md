# LegacyCore - WIP (Work in Progress)
## GD Comeback's Core
Basically a Geometry Dash Server Emulator

Supports 1.2 (tested) 

To do:

1. Implement 1.2 endpoints (I guess)
2. Make an "accounts" system (for dashboard)

Required version of PHP: 5.5+ (tested up to 8.3)

### Credits
Base for account settings and the private messaging system by someguy28

XOR encryption — https://github.com/sathoro/php-xor-cipher — (incl/lib/XORCipher.php)

Cloud save encryption — https://github.com/defuse/php-encryption — (incl/lib/defuse-crypto.phar)

Mail verification — https://github.com/phpmailer/phpmailer — (config/mail)

JQuery — https://github.com/jquery/jquery — (dashboard/lib/jq.js)

Image dominant color picker — https://github.com/swaydeng/imgcolr — (dashboard/lib/imgcolr.js)

Media cover — https://github.com/aadsm/jsmediatags — (dashboard/lib/jsmediatags.js)

Audio duration — https://github.com/JamesHeinrich/getID3 — (config/getid3)

Proxies list — https://github.com/SevenworksDev/proxy-list — (config/proxies.txt)

Common VPNs list — https://github.com/X4BNet/lists_vpn — (config/vpns.txt)

Discord Webhooks — https://github.com/renzbobz/DiscordWebhook-PHP — (config/webhooks/DiscordWebhook.php)

GD icons — https://github.com/oatmealine/gd-icon-renderer-web — (dashboard/profile/index.php)

Most of the stuff in generateHash.php has been figured out by pavlukivan and Italian APK Downloader, so credits to them
