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
 * @author          Txmod Xoops https://xoops.org 
 *                  Goffy https://myxoops.org
 *
 */
\xoops_load('XoopsFile');

/**
 * Class CreateFile.
 */
class CreateFile extends CreateTableFields
{
    /**
     * @var string
     */
    private $tab = '    ';

    /**
     * @var mixed
     */
    private $xf = null;
    /**
     * "fileName" attribute of the files.
     *
     * @var mixed
     */
    private $fileName = null;
    /**
     * "subdir" attribute of the directories.
     *
     * @var string
     */
    private $subdir = null;
    /**
     * "uploadPath" attribute of the files.
     *
     * @var string
     */
    private $uploadPath = null;
    /**
     * @var string
     */
    private $content = null;
    /**
     * @var mixed
     */
    private $created = null;
    /**
     * @var mixed
     */
    private $notCreated = null;
    /**
     * @var string
     */
    private $mode = null;
    /**
     * @var mixed
     */
    protected $phpcode = null;
    /**
     * @var mixed
     */
    protected $htmlcode;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xf = \XoopsFile::getHandler();
    }

    /**
     * @public static function getInstance
     * @param null
     * @return Modulebuilder\Files\CreateFile
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
     * @public function create
     *
     * @param $moduleDirname
     * @param $subdir
     * @param $fileName
     * @param $content
     * @param $created
     * @param $notCreated
     * @param $mode
     */
    public function create($moduleDirname, $subdir = null, $fileName = null, $content = '', $created = null, $notCreated = null, $mode = 'w+')
    {
        $this->setFileName($fileName);
        $this->created    = $created;
        $this->notCreated = $notCreated;
        $this->setMode($mode);
        $this->setRepositoryPath($moduleDirname);
        if (!empty($content) && \is_string($content)) {
            $this->setContent($content);
        }
        if (isset($subdir) && \is_string($subdir)) {
            $this->setSubDir($subdir);
        }
    }

    /**
     * @public function write
     * @param mixed $moduleDirname
     */
    /*public function write($module, $fileName)
    {
        $this->setModule($module);
        $this->setFileName($fileName);
    }*/

    /**
     * @private function setRepositoryPath
     * @param string $moduleDirname
     */
    private function setRepositoryPath($moduleDirname)
    {
        $this->uploadPath = TDMC_UPLOAD_REPOSITORY_PATH . '/' . $moduleDirname;
    }

    /**
     * @private function getRepositoryPath
     * @param null
     * @return string
     */
    private function getRepositoryPath()
    {
        return $this->uploadPath;
    }

    /**
     * @private function setSubDir
     * @param $subdir
     */
    private function setSubDir($subdir)
    {
        $this->subdir = $subdir;
    }

    /**
     * @private function getSubDir
     * @param null
     * @return string
     */
    private function getSubDir()
    {
        return $this->subdir;
    }

    /**
     * public function setFileName.
     *
     * @param $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @public function getFileName
     * @param null
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @private function setContent
     * @param $content
     */
    private function setContent($content)
    {
        //replace tabs by 4 spaces
        $this->content = preg_replace('/\t/', $this->tab, $content);
    }

    /**
     * @private function setContent
     * @param null
     * @return string
     */
    private function getContent()
    {
        return $this->content;
    }

    /**
     * @private function getFolderName
     * @param null
     * @return string
     */
    private function getFolderName()
    {
        $path = $this->getUploadPath();
        if (\mb_strrpos($path, '\\')) {
            $str = \mb_strrpos($path, '\\');
            if (false !== $str) {
                return mb_substr($path, $str + 1, mb_strlen($path));
            }

            return mb_substr($path, $str, mb_strlen($path));
        }
        //return 'root module';
        return false;
    }

    /**
     * @public function getUploadPath
     * @param null
     * @return string
     */
    private function getUploadPath()
    {
        if (null != $this->getSubDir()) {
            $ret = $this->getRepositoryPath() . '/' . $this->getSubDir();
        } else {
            $ret = $this->getRepositoryPath();
        }

        return $ret;
    }

    /**
     * @private function getCreated
     * @param null
     * @return bool
     */
    private function getCreated()
    {
        return $this->created;
    }

    /**
     * @private function getNotCreated
     * @param null
     * @return bool
     */
    private function getNotCreated()
    {
        return $this->notCreated;
    }

    /**
     * @private function setMode
     * @param $mode
     */
    private function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @private function getMode
     * @param null
     * @return string
     */
    private function getMode()
    {
        return $this->mode;
    }

    /**
     * @public function getLanguage
     * @param string $moduleDirname
     * @param string $prefix
     * @param string $suffix
     * @param string $addFq //add function qualifier
     * @return string
     */
    public function getLanguage($moduleDirname, $prefix = '', $suffix = '', $addFq = true)
    {
        $lang = '';
        if ($addFq) {
            $lang = '\\';
        }
        $lang .= '_' . $prefix . '_' . \mb_strtoupper($moduleDirname);
        $ret  = $lang;
        if (!empty($suffix) || '_' !== $suffix) {
            $ret = $lang . '_' . $suffix;
        } elseif ('_' === $suffix) {
            $ret = $lang . '_';
        }

        return $ret;
    }

    /**
     * @public function getLeftString
     * @param string $string
     *
     * @return string
     */
    public function getLeftString($string)
    {
        return mb_substr($string, 0, mb_strpos($string, '_'));
    }

    /**
     * @public function getRightString
     * @param $string
     *
     * @return string
     */
    public function getRightString($string = null)
    {
        if (mb_strpos($string, '_')) {
            $str = mb_strpos($string, '_');
            if (false !== $str) {
                $ret = mb_substr($string, $str + 1, mb_strlen($string));

                return $ret;
            }
        }

        return $string;
    }

    /**
     * @public function getCamelCase
     * @param $string
     * @param $ucfirst
     * @param $lcfirst
     *
     * @return string
     */
    public function getCamelCase($string, $ucfirst = false, $lcfirst = false)
    {
        $rightString = $this->getRightString($string);
        $leftString  = $this->getLeftString($string);
        if ($ucfirst) {
            return $this->getUcfirst($leftString) . $this->getUcfirst($rightString);
        }
        if ($lcfirst) {
            return $this->getLcfirst($leftString) . $this->getUcfirst($rightString);
        }

        return $string;
    }

    /**
     * @public function getUcfirst
     * @param $string
     *
     * @return string
     */
    public function getUcfirst($string)
    {
        return \ucfirst($string);
    }

    /**
     * @public function getLcfirst
     *
     * @param $string
     * @return string
     */
    public function getLcfirst($string)
    {
        return lcfirst($string);
    }

    /**
     * @public function getStrToUpper
     *
     * @param $string
     * @return string
     */
    public function getStrToUpper($string)
    {
        return \mb_strtoupper($string);
    }

    /**
     * @public function getStrToLower
     * @param $string
     *
     * @return string
     */
    public function getStrToLower($string)
    {
        return \mb_strtolower($string);
    }

    /**
     * @public function getRequire
     * @param $filename
     * @return string
     */
    public function getRequire($filename = 'header')
    {
        return "require __DIR__ . '/{$filename}.php';\n";
    }

    /**
     * @public function getRequireOnce
     * @param $filename
     * @return string
     */
    public function getRequireOnce($filename = 'header')
    {
        return "require_once __DIR__ . '/{$filename}.php';\n";
    }

    /**
     * @private function getDashComment
     *
     * @param $comment
     *
     * @return string
     */
    public function getDashComment($comment)
    {
        return "// ------------------- {$comment} ------------------- //\n";
    }

    /**
     * @public function getSimpleString
     * @param        $string
     *
     * @param string $t
     * @return string
     */
    public function getSimpleString($string, $t = '')
    {
        return "{$t}{$string}\n";
    }

    /**
     * @public function getHeaderFilesComments
     * @param string $module
     * @param        $noPhpFile
     *
     * @param string $namespace
     * @return string
     */
    public function getHeaderFilesComments($module, $noPhpFile = null, $namespace = '')
    {
        $pc      = Modulebuilder\Files\CreatePhpCode::getInstance();
        $name    = $module->getVar('mod_name');
        $dirname = $module->getVar('mod_dirname');
        //$version          = $module->getVar('mod_version');
        $since    = $module->getVar('mod_since');
        $minXoops = $module->getVar('mod_min_xoops');
        $author   = $module->getVar('mod_author');
        //$credits          = $module->getVar('mod_credits');
        $authorMail       = $module->getVar('mod_author_mail');
        $authorWebsiteUrl = $module->getVar('mod_author_website_url');
        $license          = $module->getVar('mod_license');
        //$subversion       = $module->getVar('mod_subversion');
        //$date             = date('D Y-m-d H:i:s');
        if (null === $noPhpFile) {
            $ret = "<?php";
            $ret .= "\n\ndeclare(strict_types=1);\n";
        } elseif (\is_string($noPhpFile)) {
            $ret = $noPhpFile;
        } else {
            $ret = '';
        }
        $ret .= "\n{$namespace}/*\n";

        $filename = TDMC_CLASS_PATH . '/Files/Docs/license.txt';
        $handle   = fopen($filename, 'rb');
        $data     = fread($handle, filesize($filename));
        fclose($handle);
        $ret       .= $data . "\n";
        $ret       .= "*/\n";
        $copyright = [
            $name           => 'module for xoops',
            ''              => '',
            '@copyright   '  => '2021 XOOPS Project (https://xoops.org)',
            '@license     '   => $license,
            '@package     '   => $dirname,
            '@since       '    => $since,
            '@min_xoops   ' => $minXoops,
        ];
        $authorLine = $author;
        if ('' !== $authorMail) {
            $authorLine .= ' - Email:' . $authorMail;
        }
        if ('' !== $authorWebsiteUrl) {
            $authorLine .= ' - Website:' . $authorWebsiteUrl;
        }
        $copyright['@author      '] = $authorLine;

        $ret       .= $pc->getPhpCodeCommentMultiLine($copyright);

        return $ret;
    }

    /**
     * @public function renderFile
     * @param null
     * @return string
     */
    public function renderFile()
    {
        $fileName   = $this->getFileName();
        $path       = $this->getUploadPath() . '/' . $fileName;
        $created    = $this->getCreated();
        $notCreated = $this->getNotCreated();
        $folderName = $this->getFolderName();
        $mode       = $this->getMode();
        $ret        = '';
        if (!$this->xf->__construct($path, true)) {
            // Force to create file if not exist
            if ($this->xf->open($mode, true)) {
                if ($this->xf->writable()) {
                    // Integration of the content in the file
                    if (!$this->xf->write($this->getContent(), $mode, true)) {
                        $ret .= \sprintf($notCreated, $fileName, $folderName);
                        $GLOBALS['xoopsTpl']->assign('created', false);
                        return $ret;
                    }
                    // Created
                    $ret .= \sprintf($created, $fileName, $folderName);
                    $GLOBALS['xoopsTpl']->assign('created', true);
                    $this->xf->close();
                } else {
                    $ret .= \sprintf($notCreated, $fileName, $folderName);
                    $GLOBALS['xoopsTpl']->assign('created', false);
                }
            } else {
                $ret .= \sprintf($notCreated, $fileName, $folderName);
                $GLOBALS['xoopsTpl']->assign('created', false);
            }
        }

        return $ret;
    }
}
