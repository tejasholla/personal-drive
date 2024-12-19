'use client'

import { useState, useRef, useCallback } from 'react'
import { router } from '@inertiajs/react'
import useClickOutside   from "../Hooks/useClickOutside.jsx";
import  CreateFolderModal  from './CreateFolderModal.jsx'




const UploadMenu = ({ bucketName, path, setStatus, setStatusMessage }) => {
    const [isMenuOpen, setIsMenuOpen] = useState(false)

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
        console.log('upload folder called');
        const files = Array.from(event.target.files || []);
        if (!files.length) return;


        setStatusMessage('Uploading...');

        const formData = new FormData();
        files.forEach(file => {
            const fileName = isFolder ? file.webkitRelativePath : file.name;
            formData.append('files[]', file, fileName);
        });
        formData.append('bucketName', bucketName);
        formData.append('path', path);

        try {
            const response = await axios.post('/s3/upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            if (response.data.ok) {
                const uploadType = isFolder ? 'Folder' : 'File(s)';
                setStatusMessage(`${uploadType} uploaded successfully!`);
                setStatus(true);

                router.visit(window.location.href, {
                    only: ['files'],
                    preserveState: true,
                });
            }

        } catch (error) {
            setStatusMessage('An error occurred. Please try again.')
            setStatus(false);
        } finally {
            setIsMenuOpen(false);
            resetFileFolderInput();
        }
    }

    return (

        <div ref={menuRef} className='relative  m-0 p-0'>
            <button className="p-2 rounded-md inline-flex w-auto bg-gray-700 m-0"
                    onClick={() => {
                        setIsMenuOpen(!isMenuOpen)
                    }}
            >
                + New
            </button>
            {isMenuOpen && (
                <div
                    className="absolute left-0 mt-2 w-32 text-left rounded-md shadow-lg bg-gray-700 ring-1 ring-black ring-opacity-5 z-10">
                    <div className="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                        <button
                            onClick={() => {
                                console.log('create folder');
                                setIsModalOpen(true) ;
                                console.log('isModalOpen', isModalOpen);
                            }}
                            className="text-left block w-full px-4 py-2 text-sm bg-gray-700 "
                            role="menuitem"
                        >
                            Create Folder
                        </button>
                        <button
                            onClick={() => fileInputRef.current.click()}
                            className="text-left block w-full px-4 py-2 text-sm bg-gray-700 "
                            role="menuitem"
                        >
                            Upload File
                        </button>
                        <button
                            onClick={() => folderInputRef.current.click()}
                            className="text-left block w-full px-4 py-2 text-sm bg-gray-700"
                            role="menuitem"
                        >
                            Upload Folder
                        </button>
                    </div>
                </div>
            )}

            <CreateFolderModal isModalOpen={isModalOpen} setIsModalOpen={setIsModalOpen} setStatusMessage={setStatusMessage}  bucketName={bucketName} path={path} setStatus={setStatus} />
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