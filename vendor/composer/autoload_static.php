<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit19673bb5bfb714afa34fbb91f2dad525
{
    public static $prefixLengthsPsr4 = array (
        'U' => 
        array (
            'Util\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Util\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Util',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit19673bb5bfb714afa34fbb91f2dad525::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit19673bb5bfb714afa34fbb91f2dad525::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit19673bb5bfb714afa34fbb91f2dad525::$classMap;

        }, null, ClassLoader::class);
    }
}
