import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.jsx';
import {Head, router} from '@inertiajs/react';
import FileManager from './FileManager.jsx';
import AlertBox from "@/Pages/Drive/Components/AlertBox.jsx";
import RefreshButton from "@/Pages/Drive/Components/RefreshButton.jsx";
import DownloadButton from "@/Pages/Drive/Components/DownloadButton.jsx";
import ShowShareModalButton from "@/Pages/Drive/Components/Shares/ShowShareModalButton.jsx";
import ShareModal from "@/Pages/Drive/Components/Shares/ShareModal.jsx";
import DeleteButton from "@/Pages/Drive/Components/DeleteButton.jsx";
import UploadMenu from "@/Pages/Drive/Components/UploadMenu.jsx";
import SearchBar from "@/Pages/Drive/Components/SearchBar.jsx";
import FileBrowserSection from "@/Pages/Drive/Components/FileBrowserSection.jsx";
import useSelectionUtil from "@/Pages/Drive/Hooks/useSelectionutil.jsx";
import {useEffect, useState} from "react";

export default function DriveHome({files, path, token}) {
    console.log('render filemanager ',);
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
    // const [status, setStatus] = useState(true)
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
        <AuthenticatedLayout
            header={<div className="flex justify-between">
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    File Manager
                </h2>
            </div>}
        >
            <Head title="File manager"
            />
            <div className="max-w-7xl mx-auto  bg-gray-800 text-gray-200">
                <div className="my-12 p-5">
                    <div className="rounded-md gap-x-2 flex items-start relative ">
                        <AlertBox message={statusMessage} />
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
                                <UploadMenu path={path} setStatusMessage={setStatusMessage} files={files}/>
                            }
                            <SearchBar handleSearch={handleSearch}/>
                        </div>
                    </div>
                    <FileBrowserSection files={files} path={path} isSearch={isSearch} token={token}
                                        setStatusMessage={setStatusMessage} selectAllToggle={selectAllToggle}
                                        handleSelectAllToggle={handleSelectAllToggle} selectedFiles={selectedFiles}
                                        handlerSelectFile={handlerSelectFileMemo}
                                        setIsShareModalOpen={setIsShareModalOpen}
                                        setFilesToShare={setFilesToShare} isAdmin={true}/>
                </div>
            </div>
        </AuthenticatedLayout>);
}
