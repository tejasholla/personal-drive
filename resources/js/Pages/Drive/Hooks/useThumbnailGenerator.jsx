import {router} from '@inertiajs/react'

const useThumbnailGenerator = (files) => {
    const generateThumbnails = async (ids) => {
        try {
            const response = await axios.post('/gen-thumbs', { ids });
            console.log('Thumbnails generated successfully:', response.data);
        } catch (error) {
            console.error('Error generating thumbnails:', error);
        }
    };

    // Filter files that need thumbnails
    const thumbnailIds = files
        .filter(file => !file.has_thumbnail && ['image', 'video'].includes(file.file_type))
        .map(file => file.id);
    if (thumbnailIds.length > 0) {
        generateThumbnails(thumbnailIds);
    }
};

export default useThumbnailGenerator;