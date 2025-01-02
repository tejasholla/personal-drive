import Modal from "@/Pages/Aws/Components/Modal.jsx";
import VideoPlayer from "./VideoPlayer.jsx";
import ImageViewer from "./ImageViewer.jsx";
import {ChevronLeft, ChevronRight} from 'lucide-react';
import {useCallback, useEffect, useRef, useState} from "react";

const MediaViewer = ({
                         selectedFileHash,
                         selectedFileType,
                         isModalOpen,
                         setIsModalOpen,
                         selectFileForPreview,
                         previewAbleFiles
                     }) => {

    const [isActive, setIsActive] = useState(false);
    const timeoutRef = useRef(null);
    let currentFileIndex = previewAbleFiles.findIndex(file => file.hash === selectedFileHash);

    function prevClick() {
        if (previewAbleFiles[currentFileIndex]['prev']) {
            let file = previewAbleFiles[--currentFileIndex];
            selectFileForPreview(file);
        }
    }

    function nextClick() {
        if (previewAbleFiles[currentFileIndex]['next']) {
            let file = previewAbleFiles[++currentFileIndex];
            selectFileForPreview(file);
        }
    }

    const handleKeyDown = useCallback((event) => {
        if (event.key === 'ArrowLeft') {
            prevClick();
        }
        if (event.key === 'ArrowRight') {
            nextClick();
        }
    }, [prevClick, nextClick]);

    useEffect(() => {
        window.addEventListener('keydown', handleKeyDown);
        window.addEventListener('mousemove', handleMouseMove);
        return () => {
            window.removeEventListener('keydown', handleKeyDown);
            window.removeEventListener('mousemove', handleMouseMove);

        };
    });

    function handleMouseMove() {
        setIsActive(true);
        if (timeoutRef.current) {
            clearTimeout(timeoutRef.current);
        }
        timeoutRef.current = setTimeout(() => {
            setIsActive(false);
        }, 1000);
    }

    return (<Modal isOpen={isModalOpen} onClose={setIsModalOpen} classes={`min-w-80  mx-auto`}>
        <div className=" mx-auto "
        >

            {previewAbleFiles && previewAbleFiles[currentFileIndex] && previewAbleFiles[currentFileIndex].prev &&
                <button onClick={prevClick}
                        className={`absolute ${isActive ? 'block' : 'hidden'}  left-32 top-1/2   p-2 rounded-full hover:bg-gray-500 bg-gray-500  opacity-40  focus:outline-none z-10`}
                >
                    <ChevronLeft className="text-white h-8 w-8 rounded-full"/>
                </button>}

            {previewAbleFiles && previewAbleFiles[currentFileIndex] && previewAbleFiles[currentFileIndex].next &&
                <button onClick={nextClick}
                        className={`absolute ${isActive ? 'block' : 'hidden'}   right-32 top-1/2   p-2 rounded-full hover:bg-gray-500 bg-gray-500  opacity-40  focus:outline-none z-10`}
                >
                    <ChevronRight className="text-white h-8 w-8 rounded-full"/>
                </button>}
            {selectedFileHash && ((selectedFileType === 'video' &&
                <VideoPlayer fileHash={selectedFileHash}/>) || (selectedFileType === 'image' &&
                <ImageViewer fileHash={selectedFileHash}/>))

            }
        </div>

    </Modal>);
};

export default MediaViewer;