<?php

use App\Services\UUIDService;
use App\Models\Setting;

beforeEach(function () {
    $this->settingMock = mock(Setting::class);

    // Set up the mock expectations
    $this->settingMock->shouldReceive('getSettingByKeyName')
        ->with('uuidForStorageFiles')
        ->andReturn('storage-123');

    $this->settingMock->shouldReceive('getSettingByKeyName')
        ->with('uuidForThumbnails')
        ->andReturn('thumb-456');

    $this->app->instance(Setting::class, $this->settingMock);
});


it('successfully initializes with valid UUIDs', function () {
    $service = new UUIDService($this->settingMock);

    expect($service->getStorageFilesUUID())->toBe('storage-123')
        ->and($service->getThumbnailsUUID())->toBe('thumb-456');
});
