import React from 'react';

import FileItem from "../FileItem.jsx";
import FolderItem from "../FolderItem.jsx";

import {useNavigate} from 'react-router-dom';
import SortIcon from "../../Svgs/SortIcon.jsx";


const ListView = ({
                      filesCopy,
                      selectAllFiles,
                      allSelected,
                      sortCol,
                      sortDetails,
                      isSearch,
                      path,
                      checkboxStates,
                      token,
                      setStatusMessage,
                      handleFileClick,
                      selectCheckbox,
                      setFilesCopy
                  }) => {
console.log('listview');
    const navigate = useNavigate();

    function handleSortClick(e,  key){
        let sortedFiles = sortCol(filesCopy, key);
        setFilesCopy(sortedFiles);
    }


    return (
        <div className="w-full">
            <hr className=" text-gray-500 border-gray-600"/>

            <div className="flex items-center justify-between text-gray-400 border-b border-b-gray-600 w-full">
                <div className="p-2 px-6 w-20 text-center hover:bg-gray-900 hover:cursor-pointer"
                     onClick={(e) => selectAllFiles(e)}>
                    <input type="checkbox" checked={allSelected} readOnly/>
                </div>
                <div onClick={(e) => handleSortClick(e, 'filename')}
                     className={`text-left w-full p-2 px-4 hover:bg-gray-900 hover:cursor-pointer ${sortDetails.key === 'filename' ? 'text-blue-400' : ''}`}>
                    <span>Name</span>
                    <SortIcon classes={`${sortDetails.key === 'filename' ? 'text-blue-500' : 'gray'} `} />
                </div>
                <div onClick={(e) => handleSortClick(e, 'size')}
                     className={`p-2 px-4 w-44 hover:bg-gray-900  hover:cursor-pointer text-right ${sortDetails.key === 'size' ? 'text-blue-400' : ''}`}>
                    <span>Size</span>
                    <SortIcon classes={`${sortDetails.key === 'size' ? 'text-blue-500' : 'gray'} `} />
                </div>
                <div onClick={(e) => handleSortClick(e, 'file_type')}
                     className={`p-2 px-4 w-44 hover:bg-gray-900  hover:cursor-pointer text-right ${sortDetails.key === 'file_type' ? 'text-blue-400' : ''}`}>
                    <span>Type</span>
                    <SortIcon classes={`${sortDetails.key === 'file_type' ? 'text-blue-500' : 'gray'} `} />
                </div>
            </div>

            {(path || isSearch) && (
                <div className="cursor-pointer hover:bg-gray-700 p-4 px-8 w-full" title="Go back"
                     onClick={() => navigate(-1)}>..</div>
            )}
            <div className=" flex flex-wrap gap-2">
                {filesCopy.map((file) => (
                    <div key={file.id}
                         className="cursor-pointer hover:bg-gray-700 group flex flex-row w-full">
                        <div className="p-2 px-6 w-20 items-center flex hover:bg-gray-900 justify-center "
                             onClick={(e) => selectCheckbox(file)}>
                            <input type="checkbox" checked={!!checkboxStates[file.id]} onChange={() => {
                            }}/>
                        </div>
                        <div className="w-full">
                            {file.is_dir ? (
                                <FolderItem
                                    file={file}
                                    isSearch={isSearch}
                                    token={token}
                                    setStatusMessage={setStatusMessage}
                                />
                            ) : (
                                <FileItem
                                    file={file}
                                    isSearch={isSearch}
                                    token={token}
                                    setStatusMessage={setStatusMessage}
                                    handleFileClick={handleFileClick}
                                />
                            )}
                        </div>
                        <div className="p-4 text-right w-44">{file.sizeText}</div>
                        <div className="p-4 text-right w-44">{file.file_type }</div>
                    </div>
                ))}
            </div>
        </div>
    );
};

export default ListView;