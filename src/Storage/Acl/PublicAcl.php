<?php

namespace MiniRest\Storage\Acl;

use Aws\S3\S3Client;

class PublicAcl implements AclInterface
{

    function putObject()
    {
        return 'public-read';
    }
}