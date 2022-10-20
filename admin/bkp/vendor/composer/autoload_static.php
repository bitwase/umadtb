<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4d16e8d028f441284ecd103e86c2d2da
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'Ifsnop\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ifsnop\\' => 
        array (
            0 => __DIR__ . '/..' . '/ifsnop/mysqldump-php/src/Ifsnop',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4d16e8d028f441284ecd103e86c2d2da::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4d16e8d028f441284ecd103e86c2d2da::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4d16e8d028f441284ecd103e86c2d2da::$classMap;

        }, null, ClassLoader::class);
    }
}
