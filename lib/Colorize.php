<?php

class Colorize
{

    protected $options = array();

    public function __construct($options)
    {
        $this->setOptions($options);
    }

    public function run()
    {
        $images = $this->getSourceImages();

        foreach ($images as $sourceImagePath) {
            $this->colorize($sourceImagePath);

        }
    }

    protected function colorize($sourceImagePath)
    {
        $targetImagePath = $this->getTargetImagePath($sourceImagePath);
        $color = $this->getColor();
        $this->colorizeImageFile($sourceImagePath, $targetImagePath, $color);
    }

    /**
     * Function for image colorization, from StackOverflow.
     *
     * @see http://stackoverflow.com/a/26792716/1824988
     *
     * @param $sourceImagePath
     * @param $targetImagePath
     * @param Color $color
     */
    protected function colorizeImageFile($sourceImagePath, $targetImagePath, $color)
    {
        $im_src = imagecreatefrompng($sourceImagePath);

        $width = imagesx($im_src);
        $height = imagesy($im_src);

        $im_dst = imagecreatefrompng($sourceImagePath);

        // Turn off alpha blending and set alpha flag
        imagealphablending($im_dst, false);
        imagesavealpha($im_dst, true);

        // Fill transparent first (otherwise would result in black background)
        imagefill($im_dst, 0, 0, imagecolorallocatealpha($im_dst, 0, 0, 0, 127));

        for ($x=0; $x<$width; $x++) {
            for ($y=0; $y<$height; $y++) {
                $alpha = (imagecolorat($im_src, $x, $y) >> 24 & 0xFF);

                $col = imagecolorallocatealpha($im_dst,
                    $color->getR() - (int) (1.0 / 255.0 * (double) $color->getR()),
                    $color->getG() - (int) (1.0 / 255.0 * (double) $color->getG()),
                    $color->getB() - (int) (1.0 / 255.0 * (double) $color->getB()),
                    (($alpha - 127) * $color->getA()) + 127
                );

                if (false === $col) {
                    die( 'sorry, out of colors...' );
                }

                imagesetpixel( $im_dst, $x, $y, $col );
            }
        }

        imagepng( $im_dst, $targetImagePath);
        imagedestroy($im_dst);
    }

    /**
     * Create directories on given path.
     *
     * @param string $path Path
     *
     * @return void
     */
    protected function createPath($path)
    {
        $dir = dirname($path);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    /**
     * Create target path for given source file. Injects suffix.
     *
     * @param string $sourceImagePath Source file path.
     *
     * @return string
     */
    protected function getTargetImagePath($sourceImagePath)
    {
        $result = $this->replaceSourceWithTargetPath($sourceImagePath);

        $this->createPath($result);

        if ($this->getSuffix() == '') {
            return $result;
        }

        $extension = $this->getPathExtension($result);
        $path      = $this->getPathWithoutExtension($result);

        $result = sprintf(
            '%s%s.%s',
            $path,
            $this->getSuffix(),
            $extension
        );

        return $result;
    }

    /**
     * In given image path, replace source path with target path.
     *
     * @param string $path Path.
     *
     * @return string
     */
    protected function replaceSourceWithTargetPath($path)
    {
        $result = $path;
        $result = str_replace(
            $this->getSourcePath(),
            $this->getTargetPath(),
            $result
        );

        return $result;
    }

    protected function getPathExtension($path)
    {
        $result = substr(strrchr($path, "."), 1);
        return $result;
    }

    protected function getPathWithoutExtension($path)
    {
        $result = substr($path, 0, strrpos($path, "."));
        return $result;
    }

    protected function getSuffix()
    {
        $result = $this->getOption('suffix');
        return $result;
    }

    protected function getColor()
    {
        $result = new Color($this->getOption('color'));
        return $result;
    }

    /**
     * Get absolute source path.
     *
     * @return string
     */
    protected function getSourcePath()
    {
        $result = $this->getOption('sourcePath');
        $result = $this->getAbsolutePath($result);

        return $result;
    }

    /**
     * Get absolute target path.
     *
     * @return string
     */
    protected function getTargetPath()
    {
        $result = $this->getOption('targetPath');
        if (!$result) {
            $result = $this->getSourcePath();
        } else {
            $result = $this->getAbsolutePath($result);
        }

        return $result;
    }

    /**
     * Make given path absolute.
     *
     * @param string $path Absolute or relative path.
     *
     * @return string
     */
    protected function getAbsolutePath($path)
    {
        $result = $path;

        if (!substr($result, 0, 1) == DIRECTORY_SEPARATOR) {
            $result = getcwd() . DIRECTORY_SEPARATOR . $result;
        }

        return $result;
    }

    /**
     * Get list of images to colorize, based on specified source path.
     *
     * @return array
     */
    protected function getSourceImages()
    {
        $sourceImagePath = $this->getSourcePath();

        $result = array();

        if (is_file($sourceImagePath)) {
            $result = array($sourceImagePath);
        } else if (is_dir($sourceImagePath)) {
            $path = $sourceImagePath . DIRECTORY_SEPARATOR;
            $path = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $path);

            $result = glob($path."*.png");
        }

        return $result;
    }

    protected function setOptions($options)
    {
        $this->options = $options;
    }

    protected function getOptions()
    {
        $result = $this->options;
        return $result;
    }

    protected function getOption($key)
    {
        $options = $this->getOptions();

        if (!isset($options[$key])) {
            return null;
        }

        $result = $options[$key];

        return $result;
    }
}
