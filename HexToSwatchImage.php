<?php

class HexToSwatchImage
{
    const WIDTH = '120';
    const HEIGHT = '120';

    /**
     * @param array $hexColors
     * @return array
     */
    public function mapHexToRGB(array $hexColors)
    {
        $mapped = [];
        foreach ($hexColors as $hexColor) {
            list($r, $g, $b) = sscanf($hexColor, "#%02x%02x%02x");
            $mapped[] = [
                'r' => $r,
                'g' => $g,
                'b' => $b
            ];
        }
        return $mapped;
    }

    /**
     * @param $canvas
     * @param array $mappedColors
     * @return array
     */
    public function mapColors($canvas, array $mappedColors)
    {
        $mapped = [];
        foreach ($mappedColors as $mappedColor) {
            array_push($mapped, imagecolorallocate($canvas, $mappedColor['r'], $mappedColor['g'], $mappedColor['b']));
        }
        return $mapped;
    }

    /**
     * @param array $hexColors
     * @return bool
     */
    function createCanvas(array $hexColors)
    {
        $flag = false;
        $hexColorsCount = count($hexColors);
        $canvas = imagecreatetruecolor(self::WIDTH, self::HEIGHT);

        switch ($hexColorsCount) {
            case 1:
                $mappedColor = $this->mapColors($canvas, $this->mapHexToRGB($hexColors));
                imagefilledrectangle($canvas, (self::WIDTH - self::WIDTH), (self::HEIGHT - self::HEIGHT), self::WIDTH, self::HEIGHT, $mappedColor[0]);
                $flag = true;
                break;
            case 2:
                $mappedColor = $this->mapColors($canvas, $this->mapHexToRGB($hexColors));
                imagefilledrectangle($canvas, (self::WIDTH - self::WIDTH), (self::HEIGHT - self::HEIGHT), (self::WIDTH / 2), self::HEIGHT, $mappedColor[0]);
                imagefilledrectangle($canvas, (self::WIDTH / 2), (self::HEIGHT - self::HEIGHT), self::WIDTH, self::HEIGHT, $mappedColor[1]);
                $flag = true;
                break;
            case 3:
                $mappedColor = $this->mapColors($canvas, $this->mapHexToRGB($hexColors));
                $pieces = [
                    [
                        (self::WIDTH - self::WIDTH), (self::HEIGHT - self::HEIGHT),
                        (self::WIDTH / 3) * 2, (self::HEIGHT - self::HEIGHT),
                        (self::WIDTH - self::WIDTH), (self::HEIGHT / 3) * 2,
                        (self::WIDTH - self::WIDTH), (self::HEIGHT - self::HEIGHT)
                    ],
                    [
                        (self::WIDTH / 3) * 2, (self::HEIGHT - self::HEIGHT),
                        self::WIDTH, (self::HEIGHT - self::HEIGHT),
                        self::WIDTH, (self::HEIGHT / 3),
                        (self::WIDTH / 3), self::HEIGHT,
                        (self::WIDTH - self::WIDTH), self::HEIGHT,
                        (self::WIDTH - self::WIDTH), (self::HEIGHT / 3) * 2,
                        (self::WIDTH / 3) * 2, (self::HEIGHT - self::HEIGHT)
                    ],
                    [
                        self::WIDTH, self::HEIGHT,
                        self::WIDTH, (self::HEIGHT / 3),
                        (self::WIDTH / 3), self::HEIGHT,
                        self::WIDTH, self::HEIGHT
                    ],
                ];
                imagefilledpolygon($canvas, $pieces[0], count($pieces[0])/2, $mappedColor[0]);
                imagefilledpolygon($canvas, $pieces[1], count($pieces[1])/2, $mappedColor[1]);
                imagefilledpolygon($canvas, $pieces[2], count($pieces[2])/2, $mappedColor[2]);
                $flag = true;
                break;
            case 4:
                $mappedColor = $this->mapColors($canvas, $this->mapHexToRGB($hexColors));
                imagefilledrectangle($canvas, (self::WIDTH - self::WIDTH), (self::HEIGHT - self::HEIGHT), (self::WIDTH / 2), (self::HEIGHT / 2), $mappedColor[0]);
                imagefilledrectangle($canvas, (self::WIDTH / 2), (self::HEIGHT - self::HEIGHT), self::WIDTH, (self::HEIGHT / 2), $mappedColor[1]);
                imagefilledrectangle($canvas, (self::WIDTH - self::WIDTH), (self::HEIGHT / 2), (self::WIDTH / 2), self::HEIGHT, $mappedColor[2]);
                imagefilledrectangle($canvas, (self::WIDTH / 2), (self::HEIGHT / 2), self::WIDTH, self::HEIGHT, $mappedColor[3]);
                $flag = true;
                break;
            default:
                $flag = false;
        }
        if ($hexColorsCount >= 1 && $hexColorsCount <= 4) {
            header('Content-Type: image/jpeg');
            imagejpeg($canvas);
            imagedestroy($canvas);
        }
        return $flag;
    }
}

$HexToSwatchImage = new \HexToSwatchImage();
$HexToSwatchImage->createCanvas(['#ff0000', '#fff400', '#ff3500', '#ff6600']);