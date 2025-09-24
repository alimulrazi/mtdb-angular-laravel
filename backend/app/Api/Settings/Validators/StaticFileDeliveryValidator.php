<?php

namespace Api\Settings\Validators;

use Api\Core\HttpClient;
use Api\Files\Actions\Deletion\PermanentlyDeleteEntries;
use Api\Files\Actions\UploadFile;
use Api\Settings\DotEnvEditor;
use Illuminate\Support\Str;

class StaticFileDeliveryValidator implements SettingsValidator
{
    const KEYS = ['static_file_delivery'];

    /**
     * @inheritDoc
     */
    public function fails($settings)
    {
        if ( ! $settings['static_file_delivery']) {
            return false;
        }

        $originalDelivery = config('common.site.static_file_delivery');
        $originalDriver = config('common.site.uploads_disk_driver');

        app(DotEnvEditor::class)
            ->write([
                'STATIC_FILE_DELIVERY' => $settings['static_file_delivery'],
                'UPLOADS_DISK_DRIVER' => 'local'
            ]);

        $previewToken = Str::random(10);
        $contents = Str::random(10);

        $fileEntry = app(UploadFile::class)->execute('private', [
            'contents' => $contents,
            'name' => 'temp #' . Str::random(5),
            'file_name' => Str::random(40),
            'preview_token' => $previewToken,
            'mime' => 'text/plain',
            'type' => 'text',
            'file_size' => 1,
            'extension' => '.txt',
        ], []);

        $response = app(HttpClient::class)->get(url($fileEntry->url) . "?preview_token=$previewToken");
        app(PermanentlyDeleteEntries::class)->execute([$fileEntry->id]);

        app(DotEnvEditor::class)
            ->write([
                'STATIC_FILE_DELIVERY' => $originalDelivery,
                'UPLOADS_DISK_DRIVER' => $originalDriver,
            ]);

        if ($contents !== $response) {
            return ['static_delivery_group' => __('Could not validate selected optimization. Is it enabled on the server?')];
        } else {
            return false;
        }
    }
}

