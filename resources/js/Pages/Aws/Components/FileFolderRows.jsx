import FolderItem from "@/Pages/Aws/Components/FolderItem.jsx";
import FileItem from "@/Pages/Aws/Components/FileItem.jsx";
import {memo, useEffect, useRef, useState} from 'react';
import {useNavigate} from 'react-router-dom';
import {StepBackIcon} from "lucide-react";
import MediaViewer from "./FileList/MediaViewer.jsx";

const FileFolderRows = memo(({files, path, isSearch, selectFile, token, deleteFiles, setStatusMessage}) => {

    const [allSelected, setAllSelected] = useState(false);
    const [filesCopy, setFilesCopy] = useState([...files]);
    const navigate = useNavigate();
    const [checkboxStates, setCheckboxStates] = useState({});
    const selectAllMode = useRef(true);
    const [isModalOpen, setIsModalOpen] = useState(false);

    const [selectedFileIndex, setSelectedFileIndex] = useState(null);
    const [selectedFileType, setSelectedFileType] = useState(null);
    let previewAbleFiles = files.filter(file => file.file_type);

    for (let i = 0; i < previewAbleFiles.length; i++) {
        previewAbleFiles[i]['next'] = previewAbleFiles[i + 1]?.hash || null;
        previewAbleFiles[i]['prev'] = previewAbleFiles[i - 1]?.hash || null;
    }

    useEffect(() => {
        setCheckboxStates({})
        setAllSelected(false);
        setFilesCopy([...files]);
        selectAllMode.current = true;
    }, [files]);


    let sortDetails = useRef({key: 'filename', order: 'desc'});


    function selectFileForPreview(file) {
        setSelectedFileIndex(file.hash);
        setSelectedFileType(file.file_type);
    }

    function handleFileClick(file) {
        if (file.file_type) {
            setIsModalOpen(true);
            selectFileForPreview(file);
        }
    }

    function sortArrayByKey(arr, key, direction) {
        return [...arr].sort((a, b) => {
            const valA = a[key]?.toLowerCase?.() || a[key];
            const valB = b[key]?.toLowerCase?.() || b[key];
            if (direction === 'desc') {
                return valA > valB ? -1 : valA < valB ? 1 : 0;
            } else {
                return valA < valB ? -1 : valA > valB ? 1 : 0;
            }
        });
    }

    function sortCol(key) {
        let sortDirectionToSet = (key === sortDetails.key ? sortDetails.order : 'desc');
        let sortedFiles = sortArrayByKey(filesCopy, key, sortDirectionToSet);
        setFilesCopy(sortedFiles);
        sortDetails.key = key;
        sortDetails.order = sortDirectionToSet === 'desc' ? 'asc' : 'desc';
    }

    function selectCheckbox(e, file) {
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


    return (<div className=" rounded-md overflow-hidden px-2 ">
        <MediaViewer selectedFileHash={selectedFileIndex} selectedFileType={selectedFileType} isModalOpen={isModalOpen}
                     setIsModalOpen={setIsModalOpen} selectFileForPreview={selectFileForPreview}
                     previewAbleFiles={previewAbleFiles}/>
        {/*<table className="w-full ">*/}
        {/*    <thead>*/}
        {/*    {filesCopy.length > 0 && <tr className="text-left text-gray-400 border-b border-b-gray-600">*/}
        {/*        <th className="p-2 px-6 w-20 text-center hover:bg-gray-900 hover:cursor-pointer "*/}
        {/*            onClick={(e) => selectAllFiles(e)}>*/}
        {/*            <input type="checkbox" checked={allSelected} readOnly/>*/}
        {/*        </th>*/}
        {/*        <th onClick={(e) => sortCol('filename')}*/}
        {/*            className={`p-2 px-4 hover:bg-gray-900 hover:cursor-pointer ${sortDetails.key === 'filename' ? 'text-blue-400' : ''}`}>*/}
        {/*            <span>Name</span>*/}
        {/*            <svg*/}
        {/*                className="w-3 h-3 ms-1.5 inline-block "*/}
        {/*                aria-hidden="true"*/}
        {/*                xmlns="http://www.w3.org/2000/svg"*/}
        {/*                fill="currentColor"*/}
        {/*                viewBox="0 0 24 24">*/}
        {/*                <path*/}
        {/*                    d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>*/}
        {/*            </svg>*/}
        {/*        </th>*/}
        {/*        <th onClick={(e) => sortCol('size')}*/}
        {/*            className={`p-2 px-4 w-32  hover:bg-gray-900  hover:cursor-pointer ${sortDetails.key === 'size' ? 'text-blue-400' : ''}`}>*/}
        {/*            <span>Size</span>*/}
        {/*            <svg*/}
        {/*                className="w-3 h-3 ms-1.5 inline-block "*/}
        {/*                aria-hidden="true"*/}
        {/*                xmlns="http://www.w3.org/2000/svg"*/}
        {/*                fill="currentColor"*/}
        {/*                viewBox="0 0 24 24">*/}
        {/*                <path*/}
        {/*                    d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>*/}
        {/*            </svg>*/}
        {/*        </th>*/}
        {/*    </tr>}*/}
        {/*    </thead>*/}
        {/*    <tbody className="border-spacing-y-4">*/}
        {/*    {(path || isSearch) && filesCopy.length > 0 &&*/}
        {/*        <tr className="cursor-pointer hover:bg-gray-700 " title="Go back">*/}
        {/*            <td className="p-4 px-9" colSpan={3} onClick={() => navigate(-1)}>..*/}
        {/*            </td>*/}
        {/*        </tr>}*/}

        {/*    {filesCopy.length === 0 && <tr>*/}
        {/*        <td className="py-20 px-9 text-center" colSpan={3}>*/}
        {/*            <div className="flex items-center justify-center gap-x-4 "><span*/}
        {/*                className="text-xl">Empty Results</span>*/}
        {/*                <button className="p-2 rounded-md inline-flex w-auto bg-gray-700" onClick={() => navigate(-1)}>*/}
        {/*                    <StepBackIcon className={`text-gray-500 inline`} size={22}/>*/}
        {/*                    <span className={`mx-1`}>Go Back</span>*/}
        {/*                </button>*/}
        {/*            </div>*/}
        {/*        </td>*/}
        {/*    </tr>}*/}
        {/*    {filesCopy.map((file) => (<tr key={file.id} className="cursor-pointer hover:bg-gray-700 group">*/}
        {/*        <td title="Select" className="px-1 hover:bg-gray-900 justify-center text-center"*/}
        {/*            onClick={(e) => selectCheckbox(e, file)}>*/}
        {/*            <input type="checkbox" checked={!!checkboxStates[file.id]} onChange={(e) => {*/}
        {/*            }}*/}
        {/*            />*/}
        {/*        </td>*/}
        {/*        <td className="">*/}
        {/*            {file.is_dir ? <FolderItem*/}
        {/*                file={file}*/}
        {/*                // isSelected={selectedItem && selectedItem.id === file.id}*/}
        {/*                isSearch={isSearch}*/}
        {/*                token={token}*/}
        {/*                deleteFiles={deleteFiles}*/}
        {/*                setStatusMessage={setStatusMessage}*/}

        {/*            /> : <FileItem*/}
        {/*                file={file}*/}
        {/*                isSearch={isSearch}*/}
        {/*                token={token}*/}
        {/*                deleteFiles={deleteFiles}*/}
        {/*                setStatusMessage={setStatusMessage}*/}
        {/*                handleFileClick={handleFileClick}*/}
        {/*            />}*/}
        {/*        </td>*/}
        {/*        <td className="p-4 text-right">*/}
        {/*            {file.sizeText}*/}
        {/*            {checkboxStates[file.id]}*/}
        {/*        </td>*/}
        {/*    </tr>))}*/}
        {/*    </tbody>*/}
        {/*</table>*/}

        <div className="w-full flex flex-wrap ">
            {filesCopy.length > 0 && (
                <div className="flex items-center justify-between text-gray-400 border-b border-b-gray-600 w-full">
                    <div className="p-2 px-6 w-20 text-center hover:bg-gray-900 hover:cursor-pointer"
                         onClick={(e) => selectAllFiles(e)}>
                        <input type="checkbox" checked={allSelected} readOnly/>
                    </div>
                    <div onClick={(e) => sortCol('filename')}
                         className={`text-left w-full p-2 px-4 hover:bg-gray-900 hover:cursor-pointer ${sortDetails.key === 'filename' ? 'text-blue-400' : ''}`}>
                        <span>Name</span>
                        <svg className="w-3 h-3 ms-1.5 inline-block " aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
                        </svg>
                    </div>
                    <div onClick={(e) => sortCol('size')}
                         className={`p-2 px-4 w-32 hover:bg-gray-900  hover:cursor-pointer ${sortDetails.key === 'size' ? 'text-blue-400' : ''}`}>
                        <span>Size</span>
                        <svg className="w-3 h-3 ms-1.5 inline-block " aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
                        </svg>
                    </div>
                </div>
            )}

            {(path || isSearch) && filesCopy.length > 0 && (
                <div className="cursor-pointer hover:bg-gray-700 p-4 px-8 w-full" title="Go back"
                     onClick={() => navigate(-1)}>..</div>
            )}

            {filesCopy.length === 0 && (
                <div className="py-20 w-full">
                    <div className="flex items-center justify-center gap-x-4 ">
                        <span className="text-xl">Empty Results</span>
                        <button className="p-2 rounded-md bg-gray-700 hover:bg-gray-600" onClick={() => navigate(-1)}>
                            <StepBackIcon className={`text-gray-500 inline`} size={22}/>
                            <span className={`mx-1`}>Go Back</span>
                        </button>
                    </div>
                </div>
            )}

            {filesCopy.map((file) => (
                <div key={file.id}
                     className="cursor-pointer hover:bg-gray-700 group flex flex-row w-full">
                    <div className="p-2 px-6 w-20 items-center flex hover:bg-gray-900 justify-center "
                         onClick={(e) => selectCheckbox(e, file)}>
                        <input type="checkbox" checked={!!checkboxStates[file.id]} onChange={() => {
                        }}/>
                    </div>
                    <div className="w-full">
                        {file.is_dir ? (
                            <FolderItem
                                file={file}
                                isSearch={isSearch}
                                token={token}
                                deleteFiles={deleteFiles}
                                setStatusMessage={setStatusMessage}
                            />
                        ) : (
                            <FileItem
                                file={file}
                                isSearch={isSearch}
                                token={token}
                                deleteFiles={deleteFiles}
                                setStatusMessage={setStatusMessage}
                                handleFileClick={handleFileClick}
                            />
                        )}
                    </div>
                    <div className="p-4 text-right w-32">{file.sizeText}</div>
                </div>
            ))}
        </div>

    </div>);
});

export default FileFolderRows;