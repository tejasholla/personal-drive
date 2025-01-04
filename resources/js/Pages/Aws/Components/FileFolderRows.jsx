import {memo, useEffect, useRef, useState} from 'react';
import {useNavigate} from 'react-router-dom';
import {Grid, List, StepBackIcon} from "lucide-react";
import MediaViewer from "./FileList/MediaViewer.jsx";
import TileViewOne from "./FileList/TileViewOne.jsx";
import ListView from "./FileList/ListView.jsx";
import Breadcrumb from "@/Pages/Aws/Components/Breadcrumb.jsx";


const FileFolderRows = memo(({files, path, isSearch, selectFile, token, handleDeleteFiles, setStatusMessage}) => {
    console.log('FileFolderRows ', files)
    let previewAbleTypes = useRef(['image', 'video']);
    let previewAbleFiles = useRef([]);
    let viewModes = ['ListView', 'TileViewOne'];
    const [currentViewMode, setCurrentViewMode] = useState(localStorage.getItem('viewMode') || viewModes[0])


    function handleViewModeClick(mode) {
        setCurrentViewMode(mode);
        localStorage.setItem('viewMode', mode);

    }

    const [filesCopy, setFilesCopy] = useState([...files]);
    const navigate = useNavigate();
    const [checkboxStates, setCheckboxStates] = useState({});
    const [allSelected, setAllSelected] = useState(false);
    const selectAllMode = useRef(true);
    const [selectedFileIndex, setSelectedFileIndex] = useState(null);
    const [selectedFileType, setSelectedFileType] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);

    let sortDetails = useRef({key: 'filename', order: 'desc'});

    useEffect(() => {
        console.log('useeffect filefolderrows');
        setCheckboxStates({})
        setAllSelected(false);
        let sortedFiles = sortCol(files, sortDetails.current.key, false);
        setFilesCopy([...sortedFiles]);
        selectAllMode.current = true;
        let previewAbleFilesPotential = sortedFiles.filter(file => previewAbleTypes.current.includes(file.file_type));
        console.log('previewAbleFilesPotential' , previewAbleFilesPotential);
        for (let i = 0; i < previewAbleFilesPotential.length; i++) {
            previewAbleFilesPotential[i]['next'] = previewAbleFilesPotential[i + 1]?.hash || null;
            previewAbleFilesPotential[i]['prev'] = previewAbleFilesPotential[i - 1]?.hash || null;
        }
        console.log('previewAbleFilesPotential' , previewAbleFilesPotential);

        previewAbleFiles.current = previewAbleFilesPotential;
    }, [files]);

    function selectFileForPreview(file) {
        setSelectedFileIndex(file.hash);
        setSelectedFileType(file.file_type);
    }

    function handleFileClick(file) {
        if (previewAbleTypes.current.includes(file.file_type)) {
            setIsModalOpen(true);
            selectFileForPreview(file);
        }
    }

    function sortArrayByKey(arr, key, direction) {
        console.log('sortby key ', arr);
        return [...arr].sort((a, b) => {

            const valA = a[key]?.toLowerCase?.() || a[key] || '';
            const valB = b[key]?.toLowerCase?.() || b[key] || '';
            console.log('a ',key, valA , valB);

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

    function selectCheckbox(file) {
        console.log('selectCheckbox ', file)
        selectFile(file);
        const newStates = {...checkboxStates, [file.id]: !checkboxStates[file.id]};
        setCheckboxStates(newStates);
    }

    function selectAllFiles() {
        let checkboxStates = {};
        for (const file of filesCopy) {
            selectFile(file, selectAllMode.current ? '1' : '0');
            checkboxStates[file.id] = selectAllMode.current;
        }
        selectAllMode.current = !selectAllMode.current;
        setCheckboxStates(checkboxStates);
        setAllSelected(prevState => !prevState);
    }


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

            <MediaViewer selectedFileHash={selectedFileIndex} selectedFileType={selectedFileType}
                         isModalOpen={isModalOpen}
                         setIsModalOpen={setIsModalOpen} selectFileForPreview={selectFileForPreview}
                         previewAbleFiles={previewAbleFiles}/>

            <div className="w-full flex flex-wrap ">

                {filesCopy.length > 0 && (
                    <>
                        {currentViewMode === 'TileViewOne' &&
                            <TileViewOne
                                filesCopy={filesCopy}
                                checkboxStates={checkboxStates}
                                token={token}
                                setStatusMessage={setStatusMessage}
                                handleFileClick={handleFileClick}
                                selectCheckbox={selectCheckbox}
                                handleDeleteFiles={handleDeleteFiles}
                                isSearch={isSearch}
                                selectAllFiles={selectAllFiles}
                                allSelected={allSelected}
                                setFilesCopy={setFilesCopy}
                                sortCol={sortCol}
                                sortDetails={sortDetails}
                            />
                        }
                        {currentViewMode === 'ListView' &&
                            <ListView
                                filesCopy={filesCopy}
                                selectAllFiles={selectAllFiles}
                                allSelected={allSelected}
                                sortCol={sortCol}
                                sortDetails={sortDetails}
                                isSearch={isSearch}
                                path={path}
                                checkboxStates={checkboxStates}
                                token={token}
                                handleDeleteFiles={handleDeleteFiles}
                                setStatusMessage={setStatusMessage}
                                handleFileClick={handleFileClick}
                                selectCheckbox={selectCheckbox}
                                setFilesCopy={setFilesCopy}
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

export default FileFolderRows;