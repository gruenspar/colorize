Colorize
========

Colorizes PNG images with a single color. Keeps alpha transparency.

Usage
-----

Colorize a single image with a single color value.

```
    ~/colorize $ bin/colorize "#ff0000" "-colored" "../test/icons/details.png"
```

Colorize a directory of images with a single color value.

```
    ~/colorize $ bin/colorize "#ff0000" "-colored" "../test/icons"
```

Parameters
----------

- color: hexadecimal color value. Can have 3 (#RGB), 6 (#RRGGBB) or 8 (#AARRGGBB) digits
- suffix: suffix to append to filename of colored file. If left empty, source file is overwritten.
- path: Filename to colorize or directory to colorize all PNG in.
