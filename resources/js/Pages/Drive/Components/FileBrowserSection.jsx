import {memo, useCallback, useEffect, useRef, useState} from 'react';
import {useNavigate} from 'react-router-dom';
import {Grid, List, StepBackIcon} from "lucide-react";
import MediaViewer from "./FileList/MediaViewer.jsx";
import TileViewOne from "./FileList/TileViewOne.jsx";
import ListView from "./FileList/ListView.jsx";
import Breadcrumb from "@/Pages/Drive/Components/Breadcrumb.jsx";
import useSelectionUtil from "@/Pages/Drive/Hooks/useSelectionutil.jsx";
import AlertBox from "@/Pages/Drive/Components/AlertBox.jsx";
import ShareModal from "@/Pages/Drive/Components/Shares/ShareModal.jsx";
import DownloadButton from "@/Pages/Drive/Components/DownloadButton.jsx";
import ShowShareModalButton from "@/Pages/Drive/Components/Shares/ShowShareModalButton.jsx";
import DeleteButton from "@/Pages/Drive/Components/DeleteButton.jsx";
import UploadMenu from "@/Pages/Drive/Components/UploadMenu.jsx";
import { usePage } from '@inertiajs/react';

const FileBrowserSection = memo(({files, path, token, isAdmin, slug}) => {

    const {
        selectAllToggle,
        handleSelectAllToggle,
        selectedFiles,
        setSelectedFiles,
        setSelectAllToggle,
        handlerSelectFileMemo
    } = useSelectionUtil();

    const { url } = usePage();

    const [isSearch, setIsSearch] = useState(false);
    const [statusMessage, setStatusMessage] = useState('')
    const [alertStatus, setAlertStatus] = useState(true)
    const [filesToShare, setFilesToShare] = useState(new Set());
    const [isShareModalOpen, setIsShareModalOpen] = useState(false);

    const navigate = useNavigate();

    // Preview
    let previewAbleTypes = useRef(['image', 'video', 'pdf', 'text']);
    let previewAbleFiles = useRef([]);
    const [previewFileIndex, setPreviewFileIndex] = useState(null);
    const [previewFileType, setPreviewFileType] = useState(null);
    const [isPreviewModalOpen, setPreviewIsModalOpen] = useState(false);

    function selectFileForPreview(file) {
        setPreviewFileIndex(file.id);
        setPreviewFileType(file.file_type);
    }

    function handleFileClick(file) {
        if (previewAbleTypes.current.includes(file.file_type)) {
            setPreviewIsModalOpen(true);
            selectFileForPreview(file);
        }
    }

    let handleFileClickM = useCallback(handleFileClick, [previewAbleFiles]);

    // view mode
    let viewModes = ['ListView', 'TileViewOne'];
    const [currentViewMode, setCurrentViewMode] = useState(localStorage.getItem('viewMode') || viewModes[0])

    function handleViewModeClick(mode) {
        setCurrentViewMode(mode);
        localStorage.setItem('viewMode', mode);
    }

    // Sorting
    const [filesCopy, setFilesCopy] = useState([...files]);
    let sortDetails = useRef({key: '', order: 'desc'});

    function sortArrayByKey(arr, key, direction) {
        return [...arr].sort((a, b) => {
            const valA = a[key]?.toLowerCase?.() || a[key] || '';
            const valB = b[key]?.toLowerCase?.() || b[key] || '';

            if (direction === 'desc') {
                return valA > valB ? -1 : valA < valB ? 1 : 0;
            } else {
                return valA < valB ? -1 : valA > valB ? 1 : 0;
            }
        });
    }

    function sortCol(files, key, changeDirection = true) {
        let sortDirectionToSet = 'desc';
        if (key === sortDetails.key) {
            sortDirectionToSet = sortDetails.order;
        }
        if (!changeDirection) {
            sortDirectionToSet = sortDirectionToSet === 'desc' ? 'asc' : 'desc';
        }
        let sortedFiles = sortArrayByKey(files, key, sortDirectionToSet);
        sortDetails.key = key;
        sortDetails.order = sortDirectionToSet === 'desc' ? 'asc' : 'desc';
        return sortedFiles;
    }

    function getPrevieAbleFiles(files) {
        let previewAbleFilesPotential = files.filter(file => previewAbleTypes.current.includes(file.file_type));
        for (let i = 0; i < previewAbleFilesPotential.length; i++) {
            previewAbleFilesPotential[i]['next'] = previewAbleFilesPotential[i + 1]?.id || null;
            previewAbleFilesPotential[i]['prev'] = previewAbleFilesPotential[i - 1]?.id || null;
        }
        return previewAbleFilesPotential;
    }

    useEffect(() => {
        // initial sort
        let previewAbleFilesPotential;
        if (sortDetails.current.key) {
            let sortedFiles = sortCol(files, sortDetails.current.key, false);
            setFilesCopy([...sortedFiles]);
            previewAbleFilesPotential = getPrevieAbleFiles(sortedFiles);
        } else {
            setFilesCopy([...files]);
            previewAbleFilesPotential = getPrevieAbleFiles(files);
        }
        // Generate previewable files
        previewAbleFiles.current = previewAbleFilesPotential;

        if (url.includes('search-files')) {
            setIsSearch(true);
        }

    }, [files]);

    useEffect(() => {
        setFilesToShare(selectedFiles);
    }, [selectedFiles]);

    return (
        <div className=" min-h-screen rounded-md overflow-hidden px-1 sm:px-2 ">

            <ShareModal isShareModalOpen={isShareModalOpen} setIsShareModalOpen={setIsShareModalOpen}
                        setSelectedFiles={setSelectedFiles} selectedFiles={filesToShare}
                        setSelectAllToggle={setSelectAllToggle} path={path}/>

            {/*breadcrumb bar*/}
            <div className="px-1 rounded-md gap-x-2 flex items-start mt-5  justify-between ">
                <AlertBox message={statusMessage} alertStatus={alertStatus}/>

                <Breadcrumb path={path} isAdmin={isAdmin}/>
                <div className="flex gap-x-5">
                    {selectedFiles.size > 0 &&
                        <div className='flex gap-x-1'>
                            <DownloadButton setSelectedFiles={setSelectedFiles} selectedFiles={selectedFiles}
                                            setStatusMessage={setStatusMessage} statusMessage={statusMessage}
                                            setSelectAllToggle={setSelectAllToggle} slug={slug} setAlertStatus={setAlertStatus}/>
                            {isAdmin &&
                                <>
                                    <ShowShareModalButton setIsShareModalOpen={setIsShareModalOpen}/>
                                    <DeleteButton setSelectedFiles={setSelectedFiles} selectedFiles={selectedFiles}
                                                  setSelectAllToggle={setSelectAllToggle}/></>
                            }
                        </div>

                    }
                    {!isSearch && isAdmin &&
                        <UploadMenu path={path} setStatusMessage={setStatusMessage} files={files}/>
                    }
                    <div>
                        <button
                            className={`p-2 mx-1 rounded-md ${currentViewMode === 'TileViewOne' ? 'bg-gray-900 border border-gray-600' : 'bg-gray-600'} hover:bg-gray-500 active:bg-gray-800`}
                            onClick={() => handleViewModeClick('TileViewOne')}
                        >
                            <Grid/>
                        </button>
                        <button
                            className={`p-2 ml-1 rounded-md ${currentViewMode === 'ListView' ? 'bg-gray-900 border border-gray-600' : 'bg-gray-600'} hover:bg-gray-500 active:bg-gray-800`}
                            onClick={() => handleViewModeClick('ListView')}
                        >
                            <List/>
                        </button>
                    </div>
                </div>
            </div>
            {/*media viewer*/}
            <MediaViewer selectedid={previewFileIndex} selectedFileType={previewFileType}
                         isModalOpen={isPreviewModalOpen}
                         setIsModalOpen={setPreviewIsModalOpen} selectFileForPreview={selectFileForPreview}
                         previewAbleFiles={previewAbleFiles} slug={slug}/>
            {/*Files viewer*/}

            <div className="px-1 my-1 sm:md-3 md:my-8">
                {filesCopy.length > 0 && (
                    <>
                        {currentViewMode === 'TileViewOne' &&
                            <TileViewOne
                                filesCopy={filesCopy}
                                token={token}
                                setStatusMessage={setStatusMessage}
                                setAlertStatus={setAlertStatus}
                                handleFileClick={handleFileClickM}
                                isSearch={isSearch}
                                sortCol={sortCol}
                                sortDetails={sortDetails}
                                setFilesCopy={setFilesCopy}
                                path={path}
                                selectedFiles={selectedFiles}
                                handlerSelectFile={handlerSelectFileMemo}
                                selectAllToggle={selectAllToggle}
                                handleSelectAllToggle={handleSelectAllToggle}
                                setIsShareModalOpen={setIsShareModalOpen}
                                setFilesToShare={setFilesToShare}
                                isAdmin={isAdmin}
                                slug={slug}
                            />
                        }
                        {currentViewMode === 'ListView' &&
                            <ListView
                                filesCopy={filesCopy}
                                token={token}
                                setStatusMessage={setStatusMessage}
                                setAlertStatus={setAlertStatus}
                                handleFileClick={handleFileClickM}
                                isSearch={isSearch}
                                sortCol={sortCol}
                                sortDetails={sortDetails}
                                setFilesCopy={setFilesCopy}
                                path={path}
                                selectedFiles={selectedFiles}
                                handlerSelectFile={handlerSelectFileMemo}
                                selectAllToggle={selectAllToggle}
                                handleSelectAllToggle={handleSelectAllToggle}
                                setIsShareModalOpen={setIsShareModalOpen}
                                setFilesToShare={setFilesToShare}
                                isAdmin={isAdmin}
                                slug={slug}
                            />
                        }
                    </>
                )}

                {filesCopy.length === 0 && (
                    <div className="py-20 w-full">
                        <div className="flex items-center justify-center gap-x-4 ">
                            <span className="text-xl">Empty Results</span>
                            <button className="p-2 rounded-md bg-gray-700 hover:bg-gray-600"
                                    onClick={() => navigate(-1)}>
                                <StepBackIcon className={`text-gray-500 inline`} size={22}/>
                                <span className={`mx-1`}>Go Back</span>
                            </button>
                        </div>
                    </div>
                )}
            </div>


        </div>
    );
});

export default FileBrowserSection;