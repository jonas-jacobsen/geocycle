<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit44be9e39c0acfab143683fe056e17f08
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Phpml\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Phpml\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-ai/php-ml/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit44be9e39c0acfab143683fe056e17f08::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit44be9e39c0acfab143683fe056e17f08::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
