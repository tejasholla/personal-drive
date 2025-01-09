import React, {useEffect, useRef, useState} from 'react';

const VideoPlayer = ({id}) => {
    const [autoplay, setAutoplay] = useState(() => {
        const savedAutoplay = localStorage.getItem('videoAutoplay');
        return savedAutoplay !== null ? JSON.parse(savedAutoplay) : false;
    });
    const videoRef = useRef(null);

    useEffect(() => {
        if (videoRef.current) {
            videoRef.current.autoplay = autoplay;
        }
    }, [autoplay]);

    const handleAutoplayToggle = () => {
        localStorage.setItem('videoAutoplay', JSON.stringify(!autoplay));
        setAutoplay(!autoplay);
    };

    return (
        <div className="flex justify-center flex-col gap-y-2">
            <video
                ref={videoRef}
                key={id}
                controls
                autoPlay={autoplay}
                className="max-w-2xl rounded-lg shadow-lg"
            >
                <source
                    src={`/fetch-file/${id}`}
                    type="video/mp4"
                />
                Your browser does not support the video tag.
            </video>
            <div className="flex items-center space-x-2">
                <input
                    type="checkbox"
                    id="autoplay"
                    checked={autoplay}
                    onChange={handleAutoplayToggle}
                    className="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                />
                <label
                    htmlFor="autoplay"
                    className="text-sm font-medium text-gray-900 dark:text-gray-300"
                >
                    Autoplay
                </label>
            </div>
        </div>);
};

export default VideoPlayer;

