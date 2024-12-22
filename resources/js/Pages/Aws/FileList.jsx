import FileItem from './Components/FileItem.jsx';
import FolderItem from './Components/FolderItem.jsx';
import {Link, router} from "@inertiajs/react";
import {DeleteIcon, DownloadIcon, HomeIcon} from 'lucide-react';
import SearchBar from "./Components/SearchBar.jsx";
import UploadMenu from "./Components/UploadMenu.jsx";
import AlertBox from "./Components/AlertBox.jsx";
import {useState} from "react";


const FileList = ({files, onSelect, onSelectFolder, selectedItem, handleSearch, bucketName, isSearch, path}) => {

    const [statusMessage, setStatusMessage] = useState('')
    const [status, setStatus] = useState(false)
    const [selectedFiles, setSelectedFiles] = useState({})

    function selectFile(file) {
        setSelectedFiles(prevSelectedFiles => {
            const newSelectedFiles = { ...prevSelectedFiles };

            if (newSelectedFiles.hasOwnProperty(file.s3key)) {
                 delete newSelectedFiles[file.s3key];
            } else {
                 newSelectedFiles[file.s3key] = file.is_dir
            }
            return newSelectedFiles;
        });
    }

    function handleStatus(response, failMessage,  successMessage ) {
        setStatusMessage(`${failMessage}: ${(response.data && response.data.message) || 'Unknown error'}`);
        setStatus(false);
        if (response.data && response.data.ok) {
            setStatusMessage(successMessage);
            setStatus(true);
            reloadFiles();
        }
    }

    async function downloadFiles() {
        let response = await selectFileOperation('/s3/download-files');

        console.log('response' , response);
        if (response.data && response.data.ok){
            for (const url of response.data.urls) {
                window.location.href = url;
            }
        }
        handleStatus(response, `Download failed`, 'Download successful');


    }

    const aFunc = () => {}
    const aFunc2 = (param,callback) => ( param)

    aFunc2(1,()=>{return 1} );


    async function deleteFiles() {
        let response = await selectFileOperation('/s3/delete-files');
        handleStatus(response, 'delete failed', 'delete successful');
    }

    function reloadFiles() {
        router.visit(window.location.href, {
            only: ['files'],
            preserveState: true,
        });
    }

    async function selectFileOperation(targetUrl) {
        return await axios.post(targetUrl, {
            fileList: selectedFiles,
            bucketName: bucketName,
            path: path,
        });
    }

    return (
        <div className="my-12 p-5">
            <div className="rounded-md gap-x-2 flex items-start relative ">
                {<AlertBox message={statusMessage} type={status ? 'success' : 'warning'}/>}
            </div>
            <div className="rounded-md gap-x-2 flex items-start mb-6  justify-between">
                <div className="p-2 gap-2 flex ">
                    {/*<Link className="p-2 rounded-md inline-flex w-auto bg-gray-700"*/}
                    {/*      onClick={() => window.history.back()}>*/}
                    {/*    <CircleArrowLeft className={`text-gray-500 inline`} size={22}/>*/}
                    {/*    <span className={`mx-1`}>Back</span>*/}
                    {/*</Link>*/}
                    <Link className="p-2 rounded-md inline-flex w-auto bg-gray-700" href='/s3dashboard'>
                        <HomeIcon className={`text-gray-500 inline`} size={22}/>
                        <span className={`mx-1`}>All Buckets</span>
                    </Link>
                    {
                        Object.entries(selectedFiles).length > 0 &&
                        <button className="p-2 rounded-md flex items-center w-auto bg-green-800" onClick={downloadFiles}>
                            <DownloadIcon className={`text-green-500 inline`} size={22}/>
                            <span className={`mx-1 text-gray-200`}>Download</span>
                        </button>
                    }
                </div>
                <div className="p-2 gap-x-2 flex ">
                    {
                        Object.entries(selectedFiles).length > 0 &&
                        <button className="p-2 rounded-md flex items-center w-auto bg-red-950" onClick={deleteFiles}>
                            <DeleteIcon className={`text-red-500 inline`} size={22}/>
                            <span className={`mx-1 text-gray-200`}>Delete</span>
                        </button>
                    }
                    <UploadMenu bucketName={bucketName} path={path} setStatus={setStatus}
                                setStatusMessage={setStatusMessage}/>
                    <SearchBar handleSearch={handleSearch}/>
                </div>
            </div>
            <hr className=" mx-2 text-gray-500 border-gray-600"/>
            <div className=" rounded-md overflow-hidden p-2 ">
                <table className="w-full ">
                    <thead>
                    <tr className="text-left text-gray-400 border-b border-b-gray-600">
                        <th className="p-2 w-20">Select</th>
                        <th className="p-2">Name</th>
                        <th className="p-2">Size</th>
                    </tr>
                    </thead>
                    <tbody className="border-spacing-y-4">
                    <tr className="cursor-pointer hover:bg-gray-700 " title="Go back">
                        <td className="p-4 px-9" colSpan={3} onClick={() => window.history.back()}>..
                        </td>
                    </tr>
                    {files.map((file) => (
                        <tr key={file.id} className="cursor-pointer hover:bg-gray-700">
                            <td title="Select" className="px-1 hover:bg-gray-900 justify-center text-center"
                                onClick={(e) => {
                                    selectFile(file);
                                    const input = e.target === 'input' ? e.target : e.target.querySelector('input');
                                    if (input) {
                                        input.checked = !input.checked;
                                    }
                                }}>
                                <input type="checkbox"/>
                            </td>
                            <td className="">
                                {file.is_dir ?
                                    <FolderItem
                                        file={file}
                                        onSelect={onSelectFolder}
                                        isSelected={selectedItem && selectedItem.id === file.id}
                                        bucketName={bucketName}
                                        isSearch={isSearch}
                                    />
                                    :
                                    <FileItem
                                        file={file}
                                        isSearch={isSearch}
                                    />
                                }
                            </td>
                            <td>
                                {file.size}
                            </td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
};

export default FileList;