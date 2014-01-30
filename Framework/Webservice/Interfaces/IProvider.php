<?php

namespace Phine\Framework\Webservice\Interfaces;

interface IProvider
{
    /**
     * Gets the webservice schema
     * @return ISchema
     */
    function GetSchema();
}
