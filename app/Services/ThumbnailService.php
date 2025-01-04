<?php

namespace App\Services;

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

    public function genThumbailsForFileIds(array $fileIds)
    {
        $filesToGenerateFor = $this->getGeneratableFiles($fileIds)->get();
        return $this->generateThumbnailsForFiles($filesToGenerateFor);
    }

    public function getGeneratableFiles(array $fileIds)
    {
        return LocalFile::getByIds($fileIds)->whereIn('file_type', ['video', 'image']);
    }

    public function generateThumbnailsForFiles(Collection $files): bool
    {
        foreach ($files as $file) {
            if (!$file->file_type) {
                continue;
            }

            switch ($file->file_type) {
                case 'video':
                    $this->generateVideoThumbnail($file);
                    break;
                case 'image':
                    $this->generateImageThumbnail($file);
                    break;
            }
        }
        return true;
    }

    private function generateVideoThumbnail(LocalFile $file): bool
    {
        $privateFilePath = $file->getPrivatePathNameForFile();

        if (!file_exists($privateFilePath)) {
            return false;
        }

        $fullFileThumbnailPath = $this->getFullFileThumbnailPath($file) ;

        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($privateFilePath);
        $video->frame(TimeCode::fromSeconds(1))->save($fullFileThumbnailPath);
        $this->imageResize($fullFileThumbnailPath, $fullFileThumbnailPath, self::IMAGESIZE);

        $file->has_thumbnail = true;
        return $file->save();
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

    private function generateImageThumbnail(LocalFile $file)
    {
        $privateFilePath = $file->getPrivatePathNameForFile();
        if (!file_exists($privateFilePath)) {
            return false;
        }

        $fullFileThumbnailPath = $this->getFullFileThumbnailPath($file);
        $this->imageResize($privateFilePath, $fullFileThumbnailPath, self::IMAGESIZE);

        $file->has_thumbnail = true;
        return $file->save();
    }

    public function imageResize(string $privateFilePath, string $fullFileThumbnailPath, int $size): Image
    {
        return Image::useImageDriver(ImageDriver::Gd)->loadFile($privateFilePath)
            ->width($size)
            ->height($size)
            ->save($fullFileThumbnailPath);
    }
}
