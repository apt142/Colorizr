#Colorizr

Colorizer is a simple REST API for retrieving color choices and performing some simple color functions.

##Design Notes

End Points:

 * get: /random => {'result':'random_hex_color'}
 * get: /darken/{color}/{percent}
 * get: /lighten/{color}/{percent}
 * get: /saturate/{color}/{percent}
 * get: /desaturate/{color}/{percent}
 * get: /greyscale/{color}

 * get: /complementary/{color}
 * get: /adjacent/{color}
 * get: /triad/{color}
 * get: /quadtrad/{color}

 * get: /help, / => Help message/Resource list

## TODO:

 // The latest color tweeted by everycolor
 * get: /everycolor

 * get: /overlay/{color}/{filtercolor}
 * get: /multiply/{color}/{filtercolor}
 * get: /screen/{color}/{filtercolor}

## Demo:
 http://colorizr.apartment142.com
