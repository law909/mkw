<?php

class Serverresize
{
    function onAfterFileUpload($currentFolder, $uploadedFile, $sFilePath)
    {
        global $config;
        $serverresizeSettings = $config['Plugin_Serverresize'];
        $this->resize($currentFolder, $uploadedFile, $sFilePath, $serverresizeSettings);

        return true;
    }

    function resize($currentFolder, $uploadedFile, $sFilePath, $settings)
    {
        require_once CKFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/Thumbnail.php";

        $nameWithoutExt = CKFinder_Connector_Utils_FileSystem::getFileNameWithoutExtension($sFilePath);
        $extension = CKFinder_Connector_Utils_FileSystem::getExtension($sFilePath);
        foreach ($settings['sizes'] as $k => $size) {
            $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
//                $newFilePath = CKFinder_Connector_Utils_FileSystem::combinePaths($currentFolder->getServerPath(), $thumbName);
            $matches = explode('x', $size);
            CKFinder_Connector_CommandHandler_Thumbnail::createThumb($sFilePath, $newFilePath, $matches[0] * 1, $matches[1] * 1, $settings['quality'], true);
        }
    }
}

$serverresize = new Serverresize();
$config['Hooks']['AfterFileUpload'][] = [$serverresize, 'onAfterFileUpload'];
if (empty($config['Plugin_Serverresize'])) {
    $config['Plugin_Serverresize'] = [
        'quality' => 80,
        'sizes' => ['100' => '100x100', '150' => '150x150', '250' => '250x250', '400' => '400x400', '1000' => '1000x800', '2000' => '2000x1600']
    ];
}