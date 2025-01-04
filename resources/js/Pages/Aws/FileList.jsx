import FileFolderRows from './Components/FileFolderRows.jsx';
import {router} from "@inertiajs/react";
import SearchBar from "./Components/SearchBar.jsx";
import UploadMenu from "./Components/UploadMenu.jsx";
import AlertBox from "./Components/AlertBox.jsx";
import DownloadButton from "./Components/DownloadButton.jsx";
import {useCallback, useState} from "react";

import RefreshButton from "@/Pages/Aws/Components/RefreshButton.jsx";
import DeleteButton from "@/Pages/Aws/Components/DeleteButton.jsx";


const FileList = ({files, handleSearch, isSearch, path, token}) => {
    console.log('filelist files ', files);
    const [statusMessage, setStatusMessage] = useState('')
    const [status, setStatus] = useState(false)
    const [selectedFiles, setSelectedFiles] = useState(new Map());

    function selectFile(file, hasSelectAllMode = '') {
        setSelectedFiles(prevSelectedFiles => {
            const newSelectedFiles = new Map(prevSelectedFiles);
            if (hasSelectAllMode === '1') {
                newSelectedFiles.set(file.id, file.is_dir); // Select all
            } else if (hasSelectAllMode === '0') {
                newSelectedFiles.delete(file.id); // Deselect all
            } else {
                newSelectedFiles.has(file.id)
                    ? newSelectedFiles.delete(file.id) // Toggle off
                    : newSelectedFiles.set(file.id, file.is_dir); // Toggle on
            }
            return newSelectedFiles;
        });
    }

    const selectFileMemo = useCallback(selectFile, [])

    function handleStatus(response, failMessage, successMessage) {
        setStatusMessage(`${failMessage}: ${(response.data && response.data.message) || 'Unknown error'}`);
        setStatus(false);
        if (response.data && response.data.ok) {
            setStatusMessage(successMessage);
            setStatus(true);
            reloadFiles();
        }
    }

    async function handleDeleteFilesFunc(deleteFilesComponentHandler) {
        let response = await deleteFilesComponentHandler();
        setSelectedFiles(new Map());
        handleStatus(response, 'delete failed', 'delete successful');
    }

    const handleDeleteFilesMemo = useCallback(handleDeleteFilesFunc, [])


    function reloadFiles() {
        console.log('reloading ..');
        router.visit(window.location.href, {
            only: ['files'], preserveState: true, preserveScroll: true
        });
    }

    async function handleRefreshBucketButton(onSuccess) {
        let response = await axios.post('/resync', {
            redirect: path
        });
        onSuccess()
        handleStatus(response, 'Resync failed', 'Resync successful');
    }

    return (<div className="my-12 p-5">
            <div className="rounded-md gap-x-2 flex items-start relative ">
                <AlertBox message={statusMessage}
                          type={status ? 'success' : 'warning'}/>
            </div>
            <div className="rounded-md gap-x-2 flex items-start mb-2  justify-between">
                <div className="p-2 gap-2 flex  text-gray-300">

                    <RefreshButton handleRefreshBucketButton={handleRefreshBucketButton}/>
                    {selectedFiles.size > 0 &&
                        <DownloadButton selectedFiles={selectedFiles} setStatusMessage={setStatusMessage}/>
                    }
                </div>
                <div className="p-2 gap-x-2 flex  text-gray-200">
                    {selectedFiles.size > 0 &&
                        <DeleteButton handleDeleteFiles={handleDeleteFilesMemo} selectedFiles={selectedFiles}/>
                    }
                    {!isSearch && <UploadMenu path={path} setStatus={setStatus}
                                setStatusMessage={setStatusMessage}
                    />}
                    <SearchBar handleSearch={handleSearch}/>
                </div>
            </div>
            <FileFolderRows files={files} path={path} isSearch={isSearch} selectFile={selectFileMemo}
                            handleDeleteFiles={handleDeleteFilesMemo} token={token} setStatusMessage={setStatusMessage}/>
        </div>
    );
};

export default FileList;