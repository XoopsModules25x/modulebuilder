<?php

namespace XoopsModules\Modulebuilder\Files;

use XoopsModules\Modulebuilder;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * modulebuilder module.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

/**
 * Class ModuleBuilderStructure.
 */
class CreateStructure
{
    /**
     * @var string
     */
    private $moduleName;
    /**
     * @var string
     */
    private $folderName;
    /**
     * @var string
     */
    private $fileName;
    /**
     * @var mixed
     */
    private $uploadPath;

    /**
     * @public function constructor class
     *
     * @param null
     */
    public function __construct()
    {
    }

    /**
     * @static function getInstance
     *
     * @param null
     *
     * @return Modulebuilder\Files\CreateStructure
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @protected function setUploadPath
     *
     * @param $path
     */
    protected function setUploadPath($path)
    {
        $this->uploadPath = $path;
    }

    /**
     * @protected function getUploadPath
     *
     * @return string $path
     */
    protected function getUploadPath()
    {
        return $this->uploadPath;
    }

    /**
     * @protected function setModuleName
     * @param $moduleName
     */
    protected function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    /**
     * @protected function getModuleName
     *
     * @return string $moduleName
     */
    protected function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * @private function setFolderName
     *
     * @param $folderName
     */
    private function setFolderName($folderName)
    {
        $this->folderName = $folderName;
    }

    /**
     * @private function getFolderName
     * @return string $folderName
     */
    private function getFolderName()
    {
        return $this->folderName;
    }

    /**
     * @private function setFileName
     *
     * @param $fileName
     */
    private function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @private function getFileName
     *
     * @return string $fileName
     */
    private function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @public function isDir
     *
     * @param $dname
     */
    public function isDir($dname)
    {
        if (!\is_dir($dname)) {
            if (!\mkdir($dname, 0755) && !\is_dir($dname)) {
                throw new \RuntimeException(\sprintf('Directory "%s" was not created', $dname));
            }
            chmod($dname, 0755);
        } else {
            chmod($dname, 0755);
        }
    }

    /**
     * @protected function makeDir
     *
     * @param string $dir
     */
    protected function makeDir($dir)
    {
        $this->isDir(\trim($dir));
    }

    /**
     * @protected function isDirEmpty
     *
     * @param string $dir
     *
     * @return string
     */
    public function isDirEmpty($dir)
    {
        $content = [];
        $handle  = \opendir($dir);
        while (false !== ($entry = \readdir($handle))) {
            if ('.' !== $entry && '..' !== $entry) {
                $content[] = $entry;
            }
        }
        \closedir($handle);
        if (\count($content) > 0) {
            return true;
        }

        return false;
    }

    /**
     * @public function addFolderPath
     *
     * @param string      $folderName
     * @param bool|string $fileName
     * @return string
     */
    private function addFolderPath($folderName, $fileName = false)
    {
        $this->setFolderName($folderName);
        if ($fileName) {
            $this->setFileName($fileName);
            $ret = $this->getUploadPath() . DS . $this->getModuleName() . DS . $this->getFolderName() . DS . $this->getFileName();
        } else {
            $ret = $this->getUploadPath() . DS . $this->getModuleName() . DS . $this->getFolderName();
        }

        return $ret;
    }

    /**
     * @public function makeDirInModule
     *
     * @param string $dirName
     */
    public function makeDirInModule($dirName)
    {
        $fname = $this->addFolderPath($dirName);
        $this->makeDir($fname);
    }

    /**
     * @public function makeDir & copy file
     *
     * @param string $folderName
     * @param string $fromFile
     * @param string $toFile
     */
    public function makeDirAndCopyFile($folderName, $fromFile, $toFile)
    {
        $dname = $this->addFolderPath($folderName);
        $this->makeDir($dname);
        $this->copyFile($folderName, $fromFile, $toFile);
    }

    /**
     * @public function copy file
     *
     * @param string $folderName
     * @param string $fromFile
     * @param string $toFile
     */
    public function copyFile($folderName, $fromFile, $toFile)
    {
        $dname = $this->addFolderPath($folderName);
        $fname = $this->addFolderPath($folderName, $toFile);
        $this->setCopy($dname, $fromFile, $fname);
    }

    /**
     * @public function setCopy
     *
     * @param string $dname
     * @param string $fromFile
     * @param string $fname
     */
    public function setCopy($dname, $fromFile, $fname)
    {
        if (\is_dir($dname)) {
            chmod($dname, 0777);
            \copy($fromFile, $fname);
        } else {
            $this->makeDir($dname);
            \copy($fromFile, $fname);
        }
    }
}
