<?php

class Color
{
    protected $colorString = '';
    protected $rgba = null;

    public function __construct($colorString)
    {
        $this->colorString = $colorString;
    }

    public function getR()
    {
        return $this->getRgbaValue('r');
    }

    public function getG()
    {
        return $this->getRgbaValue('g');
    }

    public function getB()
    {
        return $this->getRgbaValue('b');
    }

    public function getA()
    {
        return $this->getRgbaValue('a');
    }

    protected function getRgbaValue($key)
    {
        $rgba = $this->getRgba();

        if (!isset($rgba[$key])) {
            return null;
        }

        $result = $rgba[$key];

        return $result;
    }

    protected function getRgba()
    {
        if (is_null($this->rgba)) {
            $result = $this->convertHexToRgba($this->colorString);
            $this->rgba = $result;
        }

        return $this->rgba;
    }

    /**
     * @see http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
     *
     * @param string $hex
     *
     * @return array
     */
    protected function convertHexToRgba($hex)
    {
        $hex = str_replace('#', '', $hex);

        $a = 255;
        $r = 0;
        $g = 0;
        $b = 0;

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
        } elseif (strlen($hex) == 8) {
            $a = hexdec(substr($hex, 0, 2));
            $r = hexdec(substr($hex, 2, 2));
            $g = hexdec(substr($hex, 4, 2));
            $b = hexdec(substr($hex, 6, 2));
        } elseif (strlen($hex) == 6) {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        $a /= 255;

        $result = array(
            'a' => $a,
            'r' => $r,
            'g' => $g,
            'b' => $b
        );

        return $result;
    }

}
