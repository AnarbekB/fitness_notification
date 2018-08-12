<?php

if (! function_exists('kernel_path')) {
    /**
     * Get the path to the kernel folder.
     *
     * @return string
     */
    function kernel_path()
    {
        return __DIR__ . "/../../public/";
        //todo ref to container
        //return $container->get('kernel')->getRootDir();
    }
}
