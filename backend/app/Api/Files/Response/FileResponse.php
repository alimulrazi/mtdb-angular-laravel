<?php

namespace Api\Files\Response;

use Api\Files\FileEntry;

interface FileResponse
{
    /**
     * @param FileEntry $entry
     * @param array $options
     * @return mixed
     */
    public function make(FileEntry $entry, $options);
}

