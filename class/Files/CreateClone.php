<?php

namespace XoopsModules\Modulebuilder\Files;
use XoopsModules\Modulebuilder;

/**
 * Class CreateClone
 */
class CreateClone
{
    /**
     * Delete a file or recursively delete a directory
     *
     * @param string $path Path to file or directory

    public static function deleteFileFolder($path) {

        if (is_file($path)) {
            return @\unlink($path);
        }
        elseif (\is_dir($path)) {
            $scan = glob(r\trim($path,'/').'/*');
            foreach($scan as $index=>$path) {
                self::deleteFileFolder($path);
            }
            return @\rmdir($path);
        }
    }*/

    // recursive cloning script
    /**
     * @param $src_path
     * @param $dst_path
     * @param bool $replace_code
     * @param array $patKeys
     * @param array $patValues
     */
    public static function cloneFileFolder($src_path, $dst_path, $replace_code = false, $patKeys = [], $patValues =[])
    {
        // open the source directory
        $dir = \opendir($src_path);
        // Make the destination directory if not exist
        @\mkdir($dst_path);
        // Loop through the files in source directory
        while( $file = \readdir($dir) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( \is_dir($src_path . '/' . $file) ) {
                    // Recursively calling custom copy function for sub directory
                    self::cloneFileFolder($src_path . '/' . $file, $dst_path . '/' . $file, $replace_code, $patKeys, $patValues);
                } else {
                    self::cloneFile($src_path . '/' . $file, $dst_path . '/' . $file, $replace_code, $patKeys, $patValues);
                }
            }
        }
        \closedir($dir);
    }

    /**
     * @param $src_file
     * @param $dst_file
     * @param bool $replace_code
     * @param array $patKeys
     * @param array $patValues
     */
    public static function cloneFile($src_file, $dst_file, $replace_code = false, $patKeys = [], $patValues =[])
    {
        if ($replace_code) {
            $noChangeExtensions = ['jpeg', 'jpg', 'gif', 'png', 'zip', 'ttf'];
            if (\in_array(mb_strtolower(\pathinfo($src_file, PATHINFO_EXTENSION)), $noChangeExtensions)) {
                // image
                \copy($src_file, $dst_file);
            } else {
                // file, read it and replace text
                $content = file_get_contents($src_file);
                $content = \str_replace($patKeys, $patValues, $content);
                //check file name whether it contains replace code
                $path_parts = \pathinfo($dst_file);
                $path = $path_parts['dirname'];
                $file =  $path_parts['basename'];
                $dst_file = $path . '/' . \str_replace($patKeys, $patValues, $file);
                file_put_contents($dst_file, $content);
            }
        } else {
            \copy($src_file, $dst_file);
        }
    }
}
