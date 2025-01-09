import {router} from '@inertiajs/react'

const useThumbnailGenerator = (files) => {
    const generateThumbnails = async (ids) => {
        console.log('generateThumbnails ', ids);
        try {
            await router.post('/gen-thumbs', {ids});
        } catch (error) {
            console.error('Error generating thumbnails:', error);
        }
    };

    // Filter files that need thumbnails
    const thumbnailIds = files
        .filter(file => !file.has_thumbnail && ['image', 'video'].includes(file.file_type))
        .map(file => file.id);
    console.log('generating thumb for ', files, thumbnailIds);
    if (thumbnailIds.length > 0) {
        generateThumbnails(thumbnailIds);
    }
};

export default useThumbnailGenerator;