import FileBrowserSection from './Components/FileBrowserSection.jsx';
import SearchBar from "./Components/SearchBar.jsx";
import UploadMenu from "./Components/UploadMenu.jsx";
import AlertBox from "./Components/AlertBox.jsx";
import DownloadButton from "./Components/DownloadButton.jsx";
import {useCallback, useEffect, useState} from "react";

import RefreshButton from "@/Pages/Drive/Components/RefreshButton.jsx";
import DeleteButton from "@/Pages/Drive/Components/DeleteButton.jsx";
import ShowShareModalButton from "@/Pages/Drive/Components/Shares/ShowShareModalButton.jsx";
import ShareModal from "@/Pages/Drive/Components/Shares/ShareModal.jsx";
import {router} from "@inertiajs/react";


const FileManager = ({files, path, token}) => {
    console.log('filelist files ',);
    const [statusMessage, setStatusMessage] = useState('')
    const [status, setStatus] = useState(true)
    const [selectedFiles, setSelectedFiles] = useState(new Set());
    const [filesToShare, setFilesToShare] = useState(new Set());
    const [selectAllToggle, setSelectAllToggle] = useState(false);
    const [isShareModalOpen, setIsShareModalOpen] = useState(false);
    const [isSearch, setIsSearch] = useState(false);

    useEffect(() => {
        'useeffect filemanager ';
        setFilesToShare(selectedFiles);
    },[selectedFiles ]);



    async function handleSearch (e, searchText){
        e.preventDefault();
        router.post('/search-files', {query: searchText}, {
            onSuccess: () => {
                setIsSearch(true);
            }
        });
    }

    function handlerSelectFile(file) {
        setSelectedFiles(prevSelectedFiles => {
            const newSelectedFiles = new Set(prevSelectedFiles);
            newSelectedFiles.has(file.id)
                ? newSelectedFiles.delete(file.id) // Toggle off
                : newSelectedFiles.add(file.id); // Toggle on
            return newSelectedFiles;
        });
    }

    const handlerSelectFileMemo = useCallback(handlerSelectFile, [])


    function handleSelectAllToggle() {
        // if false -> select all files | else -> deselect all files
        if (selectAllToggle) {
            console.log('selectAllMode.current', selectAllToggle);
            setSelectedFiles(new Set());
            setSelectAllToggle(false);
        } else {
            console.log('selectAllMode.current', selectAllToggle);
            setSelectedFiles(new Set(files.map(file => file.id)));
            setSelectAllToggle(true);
        }
    }


    return (
        <div className="my-12 p-5">
            <div className="rounded-md gap-x-2 flex items-start relative ">
                <AlertBox message={statusMessage}
                          type={status}/>
            </div>
            <div className="rounded-md gap-x-2 flex items-start mb-2  justify-between">
                <div className="p-2 gap-2 flex  text-gray-300">

                    <RefreshButton/>
                    {selectedFiles.size > 0 &&
                        <>
                            <DownloadButton setSelectedFiles={setSelectedFiles} selectedFiles={selectedFiles}
                                            setStatusMessage={setStatusMessage} statusMessage={statusMessage}
                                            setSelectAllToggle={setSelectAllToggle}/>
                            <ShowShareModalButton setIsShareModalOpen={setIsShareModalOpen}/>
                        </>
                    }
                </div>

                <ShareModal isShareModalOpen={isShareModalOpen} setIsShareModalOpen={setIsShareModalOpen}
                            setSelectedFiles={setSelectedFiles}
                            selectedFiles={filesToShare}  setSelectAllToggle={setSelectAllToggle} path={path} />
                <div className="p-2 gap-x-2 flex  text-gray-200">
                    {selectedFiles.size > 0 &&
                        <DeleteButton setSelectedFiles={setSelectedFiles} selectedFiles={selectedFiles}
                                      setSelectAllToggle={setSelectAllToggle}/>
                    }
                    {!isSearch &&
                        <UploadMenu path={path} setStatus={setStatus} setStatusMessage={setStatusMessage}/>
                    }
                    <SearchBar handleSearch={handleSearch}/>
                </div>
            </div>
            <FileBrowserSection files={files} path={path} isSearch={isSearch} token={token}
                                setStatusMessage={setStatusMessage} selectAllToggle={selectAllToggle}
                                handleSelectAllToggle={handleSelectAllToggle} selectedFiles={selectedFiles}
                                handlerSelectFile={handlerSelectFileMemo} setIsShareModalOpen={setIsShareModalOpen} setFilesToShare={setFilesToShare}/>
        </div>
    );
};

export default FileManager;