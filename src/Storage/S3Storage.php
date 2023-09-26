<?php

namespace MiniRest\Storage;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use DateTime;
use Exception;

class S3Storage
{
    private string $awsAccessKeyId;
    private string $awsSecretAccessKey;
    private string $bucketName;
    private string $region;
    private $s3Client;

    public function __construct()
    {
        $this->awsAccessKeyId = getenv("AWS_ACCESS_KEY_ID");
        $this->awsSecretAccessKey = getenv("AWS_SECRET_ACCESS_KEY");
        $this->bucketName = getenv("AWS_BUCKET_NAME");
        $this->region = getenv("AWS_REGION");
        $this->setup();
    }

    private function setup(): void
    {
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => $this->region,
            'credentials' => [
                'key' => $this->awsAccessKeyId,
                'secret' => $this->awsSecretAccessKey,
            ],
        ]);
    }

    public function upload($remoteFilePath, $localFilePath): void
    {
        try {
            // Upload the file to S3
            $this->s3Client->putObject([
                'Bucket' => $this->bucketName,
                'Key' => $remoteFilePath,
                'SourceFile' => $localFilePath,
                'ACL' => 'private',
            ]);

            echo 'File uploaded successfully!';
        } catch (Exception $e) {
            echo 'Error uploading file: ' . $e->getMessage();
        }
    }

    public function generatePresignedUrl($objectKey, $expiration = 3600)
    {
        try {
            $currentTime = time();

            $expirationTime = $currentTime + $expiration;
            var_dump($expirationTime);

            $expirationDateTime = new DateTime('@' . $expirationTime);

            $command = $this->s3Client->getCommand('GetObject', [
                'Bucket' => $this->bucketName,
                'Key' => $objectKey,
            ]);

            $presignedRequest = $this->s3Client->createPresignedRequest($command, $expirationDateTime);

            return (string)$presignedRequest->getUri();
        } catch (AwsException $e) {
            echo 'Error generating pre-signed URL: ' . $e->getMessage();
            return null;
        }
    }
}