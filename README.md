# ocrmypdf-php

A simple PHP wrapper for OCRmyPDF

[![Latest Stable Version](http://poser.pugx.org/mishagp/ocrmypdf/v)](https://packagist.org/packages/mishagp/ocrmypdf)
[![Total Downloads](http://poser.pugx.org/mishagp/ocrmypdf/downloads)](https://packagist.org/packages/mishagp/ocrmypdf)
[![License](http://poser.pugx.org/mishagp/ocrmypdf/license)](https://packagist.org/packages/mishagp/ocrmypdf)
[![PHP Version Require](http://poser.pugx.org/mishagp/ocrmypdf/require/php)](https://packagist.org/packages/mishagp/ocrmypdf)

## Installation

Via [Composer][]:

    $ composer require mishagp/ocrmypdf

**This library depends on [OCRmyPDF][].** Please see the GitHub repository for instructions on how to install OCRmyPDF
on your platform.

## Usage

### Basic example

```php
use mishagp\OCRmyPDF\OCRmyPDF;

//Return file path of outputted, OCRed PDF
echo (new OCRmyPDF('document.pdf'))->run();

//Return file contents of outputted, OCRed PDF
echo (new OCRmyPDF('scannedImage.png'))->setOutputPDFPath(null)->run();
```

## API

_This section is a work-in-progress._

### setInputData

Pass image/PDF data loaded in memory into `ocrmypdf` directly via stdin.

```php
use mishagp\OCRmyPDF\OCRmyPDF;

//Using Imagick
$data = $img->getImageBlob();
$size = $img->getImageLength();

//Using GD
ob_start();
imagepng($img, null, 0);
$size = ob_get_length();
$data = ob_get_clean();

echo (new OCRmyPDF())
    ->setInputData($data, $size)
    ->run();
```

### setOutputPDFPath

Specify a writable path where `ocrmypdf` should generate output PDF.

```php
use mishagp\OCRmyPDF\OCRmyPDF;
echo (new OCRmyPDF('document.pdf'))
    ->setOutputPDFPath('/outputDir/ocr_document.pdf')
    ->run();
```

### setExecutable

Define a custom location of the `ocrmypdf` executable, if by any reason it is not present in the `$PATH`.

```php
use mishagp\OCRmyPDF\OCRmyPDF;
echo (new OCRmyPDF('document.pdf'))
    ->setExecutable('/path/to/ocrmypdf')
    ->run();
```

## License

ocrmypdf-php is released under the [AGPL-3.0 License][].

## Credits

Development of ocrmypdf-php is based on the [tesseract-ocr-for-php][] PHP wrapper library for `tesseract`
developed by [thiagoalessio][] and associated contributors.

[Composer]: http://getcomposer.org/

[OCRmyPDF]: https://github.com/jbarlow83/OCRmyPDF

[AGPL-3.0 License]: https://github.com/mishagp/ocrmypdf-php/blob/main/LICENSE

[tesseract-ocr-for-php]: https://github.com/thiagoalessio/tesseract-ocr-for-php

[thiagoalessio]: https://github.com/thiagoalessio
