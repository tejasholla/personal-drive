<?php

namespace App\Services;

use App\Exceptions\PersonalDriveExceptions\ImageRelatedException;
use App\Helpers\UploadFileHelper;
use App\Models\LocalFile;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Support\Collection;
use Spatie\Image\Enums\ImageDriver;
use Spatie\Image\Image;

class ThumbnailService
{
    private const  IMAGESIZE = 210;
    private LPathService $pathService;
    private string $imageExt = '.jpeg';

    public function __construct(LPathService $pathService)
    {
        $this->pathService = $pathService;
    }

    public function genThumbnailsForFileIds(array $fileIds): int
    {
        $filesToGenerateFor = $this->getGeneratableFiles($fileIds)->get();
        return $this->generateThumbnailsForFiles($filesToGenerateFor);
    }

    public function getGeneratableFiles(array $fileIds)
    {
        return LocalFile::getByIds($fileIds)->whereIn('file_type', ['video', 'image']);
    }

    public function generateThumbnailsForFiles(Collection $files): int
    {
        if (!extension_loaded('gd')) {
            throw ImageRelatedException::invalidImageDriver();
        }
        $thumbsGenerated = 0;
        foreach ($files as $file) {
            switch ($file->file_type) {
                case 'video':
                    $thumbsGenerated += $this->generateVideoThumbnail($file) ? 1 : 0;
                    break;
                case 'image':
                    $thumbsGenerated += $this->generateImageThumbnail($file) ? 1 : 0;
                    break;
            }
        }
        return $thumbsGenerated;
    }

    private function generateVideoThumbnail(LocalFile $file): bool
    {
        $privateFilePath = $file->getPrivatePathNameForFile();

        if (!file_exists($privateFilePath)) {
            return false;
        }

        $fullFileThumbnailPath = $this->getFullFileThumbnailPath($file);
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($privateFilePath);
        $video->frame(TimeCode::fromSeconds(1))->save($fullFileThumbnailPath);
        return $this->resizeImageUpdateHasThumbnail($fullFileThumbnailPath, $fullFileThumbnailPath, $file);
    }

    public function getFullFileThumbnailPath(LocalFile $file): string
    {
        $thumbnailPathDir = $this->pathService->getThumbnailDirPath();
        $fileThumbnailDirPath = $thumbnailPathDir . DIRECTORY_SEPARATOR . $file->public_path;

        if (!file_exists($fileThumbnailDirPath)) {
            UploadFileHelper::makeFolder($fileThumbnailDirPath);
        }
        $imageExt = $file->file_type === 'video' ? $this->imageExt : '';
        return $thumbnailPathDir . DIRECTORY_SEPARATOR . $file->getPublicPathname() . $imageExt;
    }

    public function resizeImageUpdateHasThumbnail(
        string $privateFilePath,
        string $fullFileThumbnailPath,
        LocalFile $file
    ): bool {
        $this->imageResize($privateFilePath, $fullFileThumbnailPath, self::IMAGESIZE);
        $file->has_thumbnail = true;
        return $file->save();
    }

    private function imageResize(string $privateFilePath, string $fullFileThumbnailPath, int $size): bool
    {
        try {
            Image::useImageDriver(ImageDriver::Gd)->loadFile($privateFilePath)
                ->width($size)
                ->height($size)
                ->save($fullFileThumbnailPath);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    private function generateImageThumbnail(LocalFile $file): bool
    {
        $privateFilePath = $file->getPrivatePathNameForFile();
        if (!file_exists($privateFilePath)) {
            return false;
        }
        $fullFileThumbnailPath = $this->getFullFileThumbnailPath($file);
        return $this->resizeImageUpdateHasThumbnail($privateFilePath, $fullFileThumbnailPath, $file);
    }
}
