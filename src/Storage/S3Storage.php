<?php

namespace MiniRest\Storage;

use AllowDynamicProperties;
use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use DateTime;
use Exception;
use MiniRest\Exceptions\UploadErrorException;
use MiniRest\Storage\Acl\AclInterface;
use MiniRest\Storage\Acl\PrivateAcl;
use Ramsey\Uuid\Uuid;

class S3Storage extends Storage
{
    private string $awsAccessKeyId;
    private string $awsSecretAccessKey;
    private string $bucketName;
    private string $region;
    private S3Client $s3Client;
    private AclInterface $acl;

    public function __construct(AclInterface $acl = new PrivateAcl())
    {
        $this->awsAccessKeyId = getenv("AWS_ACCESS_KEY_ID");
        $this->awsSecretAccessKey = getenv("AWS_SECRET_ACCESS_KEY");
        $this->bucketName = getenv("AWS_BUCKET_NAME");
        $this->region = getenv("AWS_REGION");
        $this->setup();
        $this->acl = $acl;
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

    /**
     * @throws UploadErrorException
     */
    public function upload($remoteFilePath, $localFilePath): bool
    {
        try {
            // Upload the file to S3
            $this->s3Client->putObject([
                'Bucket' => $this->bucketName,
                'Key' => $remoteFilePath,
                'SourceFile' => $localFilePath,
                'ACL' => $this->acl->putObject(),
            ]);

            return true;
        } catch (Exception $e) {
            throw new UploadErrorException($e->getMessage());
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

    public function generatePublicdUrl($objectKey)
    {
        return $this->s3Client->getObjectUrl($this->bucketName, $objectKey);
    }

}