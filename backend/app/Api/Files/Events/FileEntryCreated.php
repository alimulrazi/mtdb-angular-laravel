<?php

namespace Api\Files\Events;

use Api\Files\FileEntry;

class FileEntryCreated
{
    /**
     * @var FileEntry
     */
    public $fileEntry;

    /**
     * @var array
     */
    public $params;

    /**
     * @param FileEntry $fileEntry
     * @param array $params
     */
    public function __construct(FileEntry $fileEntry, $params = [])
    {
        $this->fileEntry = $fileEntry;
        $this->params = $params;
    }
}

