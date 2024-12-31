import React, {useState} from 'react';
import ReactDOM from 'react-dom';
import  Modal  from './Modal.jsx'
import {router} from "@inertiajs/react";


const CreateFolderModal = ({isModalOpen, setIsModalOpen, setStatusMessage, bucketName, path, setStatus  }) => {
    const [folderName, setFolderName] = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const formPostData = {};
            formPostData['bucketName'] = bucketName;
            formPostData['path'] = path;
            formPostData['folderName'] = folderName;
            setIsModalOpen(false);

            const response = await axios.post('/s3/create-folder', formPostData);
            console.log(response);
            setStatus(false);
            setStatusMessage(`Error creating folder`);
            if (response.data.ok) {
                setStatusMessage(`Folder "${folderName}" created successfully!`);
                setStatus(true);
                router.visit(window.location.href, {
                    only: ['files'],
                    preserveState: true,

                });
            }
        } catch (error) {
            setStatus(false);
            setStatusMessage(`Error creating folder: ${error.response?.data?.message || error.message}`);
        }
    }

    return (
        <Modal isOpen={isModalOpen} onClose={setIsModalOpen} title="Create Folder">
            <div className="space-y-4">
                <form onSubmit={handleSubmit} className="space-y-4 text-gray-300">
                    <div>
                        <label htmlFor="folderName" className="block text-sm font-medium ">
                            Folder Name
                        </label>
                        <input
                            type="text"
                            id="folderName"
                            value={folderName}
                            onChange={(e) => setFolderName(e.target.value)}
                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-800"
                            required
                        />
                    </div>
                    <button
                        type="submit"
                        className="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    >
                        Create Folder
                    </button>
                </form>
            </div>
        </Modal>
    );
};

export default CreateFolderModal;

