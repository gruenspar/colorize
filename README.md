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

Installation
------------

Require it using composer:

```
    "require-dev": {
        "gruenspar/colorize": "0.1.*"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/gruenspar/colorize"
        }
    ]
```

Parameters
----------

1. Color: Hexadecimal color value. Can have 3 (`#RGB`), 6 (`#RRGGBB`) or 8 (`#AARRGGBB`) digits format. Prepending `#` is optional.
2. Suffix: Suffix to append to filename of colored file. If left empty, source file is overwritten.
3. Path: Filename to colorize or directory to colorize all PNG in.
