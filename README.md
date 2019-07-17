# amoveo-logparser
Little helper to filter through the amoveo logfiles, since all my nodes are on VPS i run this with a webserver and restrict access as needed

# Setup
1. Get a Webserver and PHP
   * apt install apache2 libapache2-mod-php

2. Clone this repo into your web-root
   * cd /var/www/html
   * git clone https://github.com/Sykh/amoveo-logparser

3. Add the logfiles, the easiest way to do this is by mounting them
   * cd /var/www/html/amoveo-logparser
   * mkdir files
   * mount --bind ~/amoveo/_build/prod/rel/amoveo_core/log files/

4. Access the page via your brower, open http://your-ip/amoveo-logparser

### Things to think about
   * You have given unrestricted access to your logfiles right now, don't do this if there is money on this node or it is running channels. Secure your webserver via .htacess or other means, maybe VPN to your node and let the webserver only answer on that IP...
