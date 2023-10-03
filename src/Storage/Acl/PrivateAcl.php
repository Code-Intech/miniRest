<?php

namespace MiniRest\Storage\Acl;

class PrivateAcl implements AclInterface
{

    function putObject()
    {
        return 'private';
    }
}