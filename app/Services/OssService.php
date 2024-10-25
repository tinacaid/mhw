<?php
namespace App\Services;

use OSS\OssClient;

class OssService
{
    protected $ossClient;

    public function __construct()
    {
        $accessKeyId = config('filesystems.disks.oss.key');
        $accessKeySecret = config('filesystems.disks.oss.secret');
        $endpoint = config('filesystems.disks.oss.endpoint');
        $this->ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
    }

    public function uploadFile($object, $filePath)
    {
        return $this->ossClient->uploadFile(config('filesystems.disks.oss.bucket'), $object, $filePath);
    }
}



