import {memo, useCallback, useEffect, useRef, useState} from 'react';
import {useNavigate} from 'react-router-dom';
import {Grid, List, StepBackIcon} from "lucide-react";
import MediaViewer from "./FileList/MediaViewer.jsx";
import TileViewOne from "./FileList/TileViewOne.jsx";
import ListView from "./FileList/ListView.jsx";
import Breadcrumb from "@/Pages/Drive/Components/Breadcrumb.jsx";


const FileBrowserSection = memo(({files, path, isSearch, token, setStatusMessage, selectAllToggle,                                      handleSelectAllToggle, selectedFiles, handlerSelectFile, setIsShareModalOpen, setFilesToShare}) => {
    // console.log('FileBrowserSection ')
    const navigate = useNavigate();

    // Preview
    let previewAbleTypes = useRef(['image', 'video']);
    let previewAbleFiles = useRef([]);
    const [previewFileIndex, setPreviewFileIndex] = useState(null);
    const [previewFileType, setPreviewFileType] = useState(null);
    const [isPreviewModalOpen, setPreviewIsModalOpen] = useState(false);

    function selectFileForPreview(file) {
        setPreviewFileIndex(file.hash);
        setPreviewFileType(file.file_type);
    }

    function handleFileClick(file) {
        if (previewAbleTypes.current.includes(file.file_type)) {
            setPreviewIsModalOpen(true);
            selectFileForPreview(file);
        }
    }
    let handleFileClickM = useCallback(handleFileClick,[previewAbleFiles]);

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
        console.log('sortby key ', arr);
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
        let sortDirectionToSet =  'desc';
        if (key === sortDetails.key ){
            sortDirectionToSet = sortDetails.order;
        }
        if (!changeDirection){
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
            previewAbleFilesPotential[i]['next'] = previewAbleFilesPotential[i + 1]?.hash || null;
            previewAbleFilesPotential[i]['prev'] = previewAbleFilesPotential[i - 1]?.hash || null;
        }
        return previewAbleFilesPotential;
    }

    useEffect(() => {
        // console.log('useeffect filefolderrows');
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
    }, [files]);


    return (
        <div className=" rounded-md overflow-hidden px-2 ">
            <div className="rounded-md gap-x-2 flex items-start mb-3  justify-start relative">
                <Breadcrumb path={path}/>
                <div className="flex justify-end absolute right-0">
                    <button
                        className={`p-2 mx-1 rounded-md ${currentViewMode === 'TileViewOne' ? 'bg-gray-900 border border-gray-700' : 'bg-gray-600'} hover:bg-gray-500`}
                        onClick={() => handleViewModeClick('TileViewOne')}
                    >
                        <Grid/>
                    </button>
                    <button
                        className={`p-2 mx-1 rounded-md ${currentViewMode === 'ListView' ? 'bg-gray-900 border border-gray-700' : 'bg-gray-600'} hover:bg-gray-500`}
                        onClick={() => handleViewModeClick('ListView')}
                    >
                        <List/>
                    </button>
                </div>
            </div>

            <MediaViewer selectedFileHash={previewFileIndex} selectedFileType={previewFileType}
                         isModalOpen={isPreviewModalOpen}
                         setIsModalOpen={setPreviewIsModalOpen} selectFileForPreview={selectFileForPreview}
                         previewAbleFiles={previewAbleFiles}/>

            <div className="w-full flex flex-wrap ">

                {filesCopy.length > 0 && (
                    <>
                        {currentViewMode === 'TileViewOne' &&
                            <TileViewOne
                                filesCopy={filesCopy}
                                token={token}
                                setStatusMessage={setStatusMessage}
                                handleFileClick={handleFileClickM}
                                isSearch={isSearch}
                                sortCol={sortCol}
                                sortDetails={sortDetails}
                                setFilesCopy={setFilesCopy}
                                selectedFiles={selectedFiles}
                                handlerSelectFile={handlerSelectFile}
                                selectAllToggle={selectAllToggle}
                                handleSelectAllToggle={handleSelectAllToggle}
                                setIsShareModalOpen={setIsShareModalOpen}
                                setFilesToShare={setFilesToShare}
                            />
                        }
                        {currentViewMode === 'ListView' &&
                            <ListView
                                filesCopy={filesCopy}
                                token={token}
                                setStatusMessage={setStatusMessage}
                                handleFileClick={handleFileClickM}
                                isSearch={isSearch}
                                sortCol={sortCol}
                                sortDetails={sortDetails}
                                setFilesCopy={setFilesCopy}
                                path={path}
                                selectedFiles={selectedFiles}
                                handlerSelectFile={handlerSelectFile}
                                selectAllToggle={selectAllToggle}
                                handleSelectAllToggle={handleSelectAllToggle}
                                setIsShareModalOpen={setIsShareModalOpen}
                                setFilesToShare={setFilesToShare}
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