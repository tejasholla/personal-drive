'use client'

import {useEffect, useRef, useState} from 'react'
import {router} from '@inertiajs/react'
import useClickOutside from "../Hooks/useClickOutside.jsx";
import CreateFolderModal from './CreateFolderModal.jsx'
import useThumbnailGenerator from "@/Pages/Drive/Hooks/useThumbnailGenerator.jsx";
import {UploadCloudIcon} from "lucide-react";


const UploadMenu = ({path, setStatusMessage, files}) => {
    const [isMenuOpen, setIsMenuOpen] = useState(false)
    const [uploadedFiles, setUploadedFiles] = useState([]);

    const fileInputRef = useRef(null)
    const folderInputRef = useRef(null)
    const resetFileFolderInput = () => {
        if (fileInputRef.current) {
            fileInputRef.current.value = ''; // Clears the selected files
        }
        if (folderInputRef.current) {
            folderInputRef.current.value = ''; // Clears the selected files
        }
    };

    const menuRef = useRef(null);
    useClickOutside(menuRef, () => setIsMenuOpen(false));

    const [isModalOpen, setIsModalOpen] = useState(false);

    const uploadFile = async (event, isFolder = false) => {
        let selectedFileForUpload = Array.from(event.target.files || []);
        if (!selectedFileForUpload.length) return;

        setStatusMessage('Uploading...');

        const formData = new FormData();
        selectedFileForUpload.forEach(file => {
            const fileName = isFolder ? file.webkitRelativePath : file.name;
            formData.append('files[]', file, fileName);
        });
        formData.append('path', path);

        router.post('/upload', formData, {
            only: ['files', 'flash'],
            onSuccess: (response) => {
                setUploadedFiles(selectedFileForUpload);
            },
            onError: (error) => {
                if (error.response?.status === 413) {
                    setStatusMessage('File too large for server to handle. Please upload a smaller file.');
                }
            },
            onFinish: () => {
                setStatusMessage('');
                setIsMenuOpen(false);
                resetFileFolderInput();
            }
        });
    }

    useEffect(() => {
        if (uploadedFiles.length > 0) {
            useThumbnailGenerator(files, path);
        }
    }, [uploadedFiles]);
    return (
        <div ref={menuRef} className='relative  m-0 p-0'>
            <button className="inline-flex gap-x-1 bg-blue-700 text-white font-bold py-2 px-2 rounded hover:bg-blue-600 active:bg-blue-800
"
                    onClick={() => {
                        setIsMenuOpen(!isMenuOpen)
                    }}
            >
                <UploadCloudIcon/>
                New
            </button>
            {isMenuOpen && (
                <div
                    className="absolute left-0 mt-2 w-32 text-left rounded-md shadow-lg bg-gray-700 ring-1 ring-black ring-opacity-5 z-10">
                    <div className="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                        <button
                            onClick={() => {
                                setIsModalOpen(true);
                            }}
                            className="text-left block w-full px-4 py-2 text-sm bg-gray-700 hover:bg-gray-600 active:bg-gray-800 "
                            role="menuitem"
                        >
                            Create Folder
                        </button>
                        <button
                            onClick={() => fileInputRef.current.click()}
                            className="text-left block w-full px-4 py-2 text-sm bg-gray-700  hover:bg-gray-600 active:bg-gray-800"
                            role="menuitem"
                        >
                            Upload File
                        </button>
                        <button
                            onClick={() => folderInputRef.current.click()}
                            className="text-left block w-full px-4 py-2 text-sm bg-gray-700 hover:bg-gray-600 active:bg-gray-800"
                            role="menuitem"
                        >
                            Upload Folder
                        </button>
                    </div>
                </div>
            )}
            <CreateFolderModal isModalOpen={isModalOpen} setIsModalOpen={setIsModalOpen} path={path}/>

            <div className="relative inline-block">
                <input
                    type="file"
                    ref={fileInputRef}
                    className="hidden"
                    onChange={(e) => uploadFile(e)}
                    multiple
                />
                <input
                    type="file"
                    ref={folderInputRef}
                    className="hidden"
                    onChange={(e) => uploadFile(e, true)}
                    webkitdirectory="true"
                    directory="true"
                />
            </div>

        </div>
    )
}

export default UploadMenu;