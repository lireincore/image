# Image effects, thumbnails and postprocessing

[![Latest Stable Version](https://poser.pugx.org/lireincore/image/v/stable)](https://packagist.org/packages/lireincore/image)
[![Total Downloads](https://poser.pugx.org/lireincore/image/downloads)](https://packagist.org/packages/lireincore/image)
[![License](https://poser.pugx.org/lireincore/image/license)](https://packagist.org/packages/lireincore/image)

## About

Supports GD, Imagick and Gmagick.

Also, you can use a special extension [lireincore/imgcache](https://github.com/lireincore/imgcache) that adds the ability to cache thumbs.

## Install

Add the `"lireincore/image": "~0.1.0"` package to your `require` section in the `composer.json` file

or

``` bash
$ php composer.phar require lireincore/image
```

## Usage

```php
//Use basic effects
use LireinCore\Image\Image;
use LireinCore\Image\PostProcessors\OptiPng;

$image = (new Image())
    ->open('/path/to/image.jpg')
    ->resize(1000, 500)
    ->grayscale()
    ->blur(2)
    ->text('Hello word', 'Verdana');
    ->save('/path/to/new_image.png', ['format' => 'png', 'png_compression_level' => 7]);

$postProcessor = new OptiPng();
$postProcessor->process('/path/to/new_image.png'); //optimize image

//Also you can add extended effects
use LireinCore\Image\Image;
use LireinCore\Image\Effects\Overlay;
use LireinCore\Image\Effects\ScaleDown;
use LireinCore\Image\Effects\Fit;
use LireinCore\Image\PostProcessors\JpegOptim;

$image = (new Image(ImageInterface::DRIVER_GD))
    ->open('/path/to/image.jpg')
    ->apply(new Overlay('/path/to/watermark.png', 70, 'right', 'bottom', '50%', '50%'))
    ->grayscale()
    ->apply(new ScaleDown('50%', '50%', true))
    ->apply(new Fit('center', 'center', '200', '90', '#f00', 20, true))
    ->negative()
    ->save('/path/to/new_image.jpg');

$postProcessor = new JpegOptim();
$postProcessor->process('/path/to/new_image.jpg'); //optimize image
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.