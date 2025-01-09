import FileBrowserSection from './Components/FileBrowserSection.jsx';
import SearchBar from "./Components/SearchBar.jsx";
import UploadMenu from "./Components/UploadMenu.jsx";
import AlertBox from "./Components/AlertBox.jsx";
import DownloadButton from "./Components/DownloadButton.jsx";
import {useEffect, useState} from "react";
import useSelectionUtil from "./Hooks/useSelectionutil.jsx";

import RefreshButton from "@/Pages/Drive/Components/RefreshButton.jsx";
import DeleteButton from "@/Pages/Drive/Components/DeleteButton.jsx";
import ShowShareModalButton from "@/Pages/Drive/Components/Shares/ShowShareModalButton.jsx";
import ShareModal from "@/Pages/Drive/Components/Shares/ShareModal.jsx";
import {router} from "@inertiajs/react";


const FileManager = ({files, path, token}) => {

    const {
        selectAllToggle,
        handleSelectAllToggle,
        selectedFiles,
        setSelectedFiles,
        setSelectAllToggle,
        handlerSelectFileMemo
    } = useSelectionUtil();

    console.log('filelist files ',);

    const [statusMessage, setStatusMessage] = useState('')
    const [status, setStatus] = useState(true)
    const [filesToShare, setFilesToShare] = useState(new Set());
    const [isShareModalOpen, setIsShareModalOpen] = useState(false);
    const [isSearch, setIsSearch] = useState(false);

    useEffect(() => {
        'useeffect filemanager ';
        setFilesToShare(selectedFiles);
    }, [selectedFiles]);


    async function handleSearch(e, searchText) {
        e.preventDefault();
        router.post('/search-files', {query: searchText}, {
            onSuccess: () => {
                setIsSearch(true);
            }
        });
    }

    return (
        <div className="my-12 p-5">
            <div className="rounded-md gap-x-2 flex items-start relative ">
                <AlertBox message={statusMessage} type={status}/>
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
                            setSelectedFiles={setSelectedFiles} selectedFiles={filesToShare}
                            setSelectAllToggle={setSelectAllToggle} path={path}/>
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
                                handlerSelectFile={handlerSelectFileMemo} setIsShareModalOpen={setIsShareModalOpen}
                                setFilesToShare={setFilesToShare}/>
        </div>
    );
};

export default FileManager;