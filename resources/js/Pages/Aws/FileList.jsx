import FileFolderRows from './Components/FileFolderRows.jsx';
import {Link, router} from "@inertiajs/react";
import {DeleteIcon, DownloadIcon, HomeIcon} from 'lucide-react';
import SearchBar from "./Components/SearchBar.jsx";
import UploadMenu from "./Components/UploadMenu.jsx";
import AlertBox from "./Components/AlertBox.jsx";
import {usePage} from '@inertiajs/react'
import {useState, useEffect, useRef, useCallback} from "react";
import Cookies from 'js-cookie';


import RefreshButton from "@/Pages/Aws/Components/RefreshButton.jsx";


const FileList = ({files, handleSearch, isSearch, path, token}) => {
    console.log('rendering filelist ', files);
    const {flash} = usePage().props

    const [statusMessage, setStatusMessage] = useState('')
    const [status, setStatus] = useState(false)
    const [hasSelectedFiles, setHasSelectedFiles] = useState(false)
    // let hasSelectedFiles = false;
    const [selectedFiles, setSelectedFiles] = useState(new Map());


    // useEffect(() => {
    //     if (flash && flash.message ) {
    //         setStatusMessage(flash.message);
    //         console.log('stat set' , flash.message);
    //     }
    // }, []);

    function selectFile(file) {
        setSelectedFiles(prevSelectedFiles => {
            const newSelectedFiles = new Map(prevSelectedFiles);
            if (newSelectedFiles.has(file.id)) {
                newSelectedFiles.delete(file.id);
            } else {
                newSelectedFiles.set(file.id, file.is_dir);
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

    async function deleteFiles() {
        let response = await selectFileOperation('/s3/delete-files');
        handleStatus(response, 'delete failed', 'delete successful');
    }

    function reloadFiles() {
        router.visit(window.location.href, {
            only: ['files'], preserveState: true,
        });
    }

    async function selectFileOperation(targetUrl) {
        console.log('selectedFiles', Object.fromEntries(selectedFiles))
        let axiosResponse = await axios.post(targetUrl, {
            fileList: JSON.stringify(Object.fromEntries(selectedFiles)), path: path,
        });
        setSelectedFiles(new Map());
        return axiosResponse;
    }


    function handleRefreshBucketButton(onSuccess) {
        router.post('/resync', {
            redirect: path
        }, {onSuccess: onSuccess})
    }

    return (<div className="my-12 p-5">
        <div className="rounded-md gap-x-2 flex items-start relative ">
            <AlertBox message={flash.message || statusMessage}
                      type={flash.status || status ? 'success' : 'warning'}/>
        </div>
        <div className="rounded-md gap-x-2 flex items-start mb-6  justify-between">
            <div className="p-2 gap-2 flex ">
                <Link className="p-2 rounded-md inline-flex w-auto bg-gray-700" href='/drive'>
                    <HomeIcon className={`text-gray-500 inline`} size={22}/>
                    <span className={`mx-1`}>Go to base dir</span>
                </Link>
                <RefreshButton handleRefreshBucketButton={handleRefreshBucketButton}/>
                {selectedFiles.size > 0 && <form method="post" action='/s3/download-files'>
                    <input type="hidden" name="_token" value={token}/>
                    <input type="hidden" name="fileList" value={JSON.stringify(Object.fromEntries(selectedFiles))}/>
                    <button type="submit" className="p-2 rounded-md flex items-center w-auto bg-green-800 bg-"
                    >
                        <DownloadIcon className={`text-green-500 inline`} size={22}/>
                        <span className={`mx-1 text-gray-200`}>Download</span>
                    </button>

                </form>

                }
            </div>
            <div className="p-2 gap-x-2 flex ">
                {selectedFiles.size > 0 &&
                    <button className="p-2 rounded-md flex items-center w-auto bg-red-950" onClick={deleteFiles}>
                        <DeleteIcon className={`text-red-500 inline`} size={22}/>
                        <span className={`mx-1 text-gray-200`}>Delete</span>
                    </button>}
                <UploadMenu path={path} setStatus={setStatus}
                            setStatusMessage={setStatusMessage}/>
                <SearchBar handleSearch={handleSearch}/>
            </div>
        </div>
        <hr className=" mx-2 text-gray-500 border-gray-600"/>
        <FileFolderRows files={files} path={path} isSearch={isSearch} selectFile={selectFileMemo}/>
    </div>);
};

export default FileList;