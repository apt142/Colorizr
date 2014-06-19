#Colorizr

Colorizer is a simple REST API for retrieving color choices and performing some simple color functions.

##Design Notes

End Points:

get: /complimentary/f00 => {"compliment":"00ffff"}
get: /random => {'color':'random_hex_color'}


### TODO:

// The latest color tweeted by everycolor
get: /everycolor

// Darkens a color
get: /darken/{color}/{percent}

// Lightens a color
get: /lighten/{color}/{percent}

Saturate
Desaturate
Greyscale
Overlay
Multiply
Screen
Help
