### Setup Theme

First, please make sure you have installed node.js, npm, composer and gulp on your system.

Everything required to setup this theme is within the shell script. Simply run sh init.sh from your terminal window to setup.

### Browsersync

Update line 6 of package.json as required for running `Browsersync` in your local browser.

### Compiling code

`gulp`

This will be the same as above, but also refresh your browser as soon as you make changes to your code.

`gulp build --prod`

This will compile all code and other assets into the distribution folder (optimised for production usage).
