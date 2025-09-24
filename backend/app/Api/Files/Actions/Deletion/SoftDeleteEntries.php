<?php

namespace Api\Files\Actions\Deletion;

use Api\Files\Events\FileEntriesDeleted;
use Api\Files\FileEntry;
use Illuminate\Support\Collection;
use Api\Files\Traits\LoadsAllChildEntries;

class SoftDeleteEntries
{
    use LoadsAllChildEntries;

    /**
     * @var FileEntry
     */
    protected $entry;

    /**
     * @param FileEntry $entry
     */
    public function __construct(FileEntry $entry)
    {
        $this->entry = $entry;
    }

    /**
     * @param Collection|array $entryIds
     * @return void
     */
    public function execute($entryIds)
    {
        collect($entryIds)->chunk(50)->each(function($ids) {
            $entries = $this->entry->withTrashed()->whereIn('id', $ids)->get();
            $this->delete($entries);
        });
    }

    /**
     * Move specified entries to "trash".
     *
     * @param Collection $entries
     * @return void
     */
    protected function delete(Collection $entries)
    {
        $entries = $this->loadChildEntries($entries);
        $this->entry->whereIn('id', $entries->pluck('id'))->delete();
        event(new FileEntriesDeleted($entries->pluck('id')->toArray(), false));
    }
}

