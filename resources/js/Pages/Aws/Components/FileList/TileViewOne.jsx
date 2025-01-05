import React, {useEffect} from 'react';
import {File, Folder} from 'lucide-react';

import DownloadButton from "../DownloadButton.jsx";
import DeleteButton from "@/Pages/Aws/Components/DeleteButton.jsx";
import {Link, router} from '@inertiajs/react'
import SortIcon from "../../Svgs/SortIcon.jsx";


const TileViewOne = ({
                         filesCopy,
                         checkboxStates,
                         token,
                         setStatusMessage,
                         handleFileClick,
                         selectCheckbox,
                         isSearch,
                         selectAllFiles,
                         allSelected,
                         setFilesCopy,
                         sortCol,
                         sortDetails
                     }) => {
    console.log('TileViewOne ', filesCopy)


    useEffect(() => {
        console.log('tileviewone useeffect ');
        function genThumbs(fileHashes) {
            router.post('/gen-thumbs', {hashes: fileHashes});
        }

        const fileHashes = [];
        for (const file of filesCopy) {
            if (!file.has_thumbnail && (file.file_type === 'image' || file.file_type === 'video')) {
                fileHashes.push(file.hash);
            }
        }

        if (fileHashes.length) {
            genThumbs(fileHashes);
        }
    }, []);

    function handleSortClick(e, key) {
        let sortedFiles = sortCol(filesCopy, key);
        setFilesCopy(sortedFiles);
    }

    return (
        <div className="w-full flex flex-col flex-wrap bg-gray-900/20 px-2">
            <div className=" text-center flex items-center gap-x-2 justify-between my-2 text-sm text-gray-400">
                <div className="text-center hover:bg-gray-900 hover:cursor-pointer flex items-center gap-x-2 p-2"
                     onClick={(e) => selectAllFiles(e)}>
                    <input className=" hover:cursor-pointer" type="checkbox" checked={allSelected} readOnly/>
                    <label className=" hover:cursor-pointer">Select All</label>
                </div>
                <div className="hover:cursor-pointer flex items-center gap-x-2">
                    <label>Sort by:</label>
                    <button
                        className={`p-1 mx-1 rounded-md bg-gray-700 hover:bg-gray-500  ${sortDetails.key === 'filename' ? 'bg-gray-900 border border-gray-500/80 text-blue-400' : ''}`}
                        onClick={(e) => handleSortClick(e, 'filename')}
                    >
                        Name <SortIcon classes={`${sortDetails.key === 'filename' ? 'text-blue-500' : 'gray'} `} />


                    </button>
                    <button
                        className={`p-1 mx-1 rounded-md bg-gray-700 hover:bg-gray-500  ${sortDetails.key === 'file_type' ? 'bg-gray-900 border border-gray-500/80  text-blue-400' : ''}`}
                        onClick={(e) => handleSortClick(e, 'file_type')}
                    >
                        Type <SortIcon classes={`${sortDetails.key === 'file_type' ? 'text-blue-500' : 'gray'} `} />

                    </button>
                    <button
                        className={`p-1 mx-1 rounded-md bg-gray-700 hover:bg-gray-500  ${sortDetails.key === 'size' ? 'bg-gray-900 border border-gray-500/80 text-blue-400' : ''}`}
                        onClick={(e) => handleSortClick(e, 'size')}
                    >
                        Size <SortIcon classes={`${sortDetails.key === 'size' ? 'text-blue-500' : 'gray'} `} />

                    </button>
                </div>
            </div>
            <div className="w-full flex flex-wrap gap-2">
                {filesCopy.map((file, index) => {
                    console.log(file);

                    const selectedFileMap = new Map([[file.id, 0]]);

                    return (
                        <div key={index}
                             className={`group relative overflow-hidden rounded-lg border border-gray-800 bg-gray-900/50 p-3 transition-all duration-200 hover:border-gray-700 hover:shadow-lg w-[295px] flex flex-col justify-between  ${checkboxStates[file.id] ? 'bg-gray-950' : ''} `}
                        >

                            <div className="">
                                {/* Filename and Checkbox Header */}
                                <div className="flex items-center justify-between relative">
                                    <h3 className=" font-medium truncate max-w-[230px] text-sm text-gray-400 mb-3 mt-1">
                                        {(isSearch ? file.public_path + '/': '') + file.filename}
                                    </h3>
                                    <div
                                        className="hover:bg-gray-600 p-2 pb-4 pl-4 cursor-pointer absolute -right-2 -top-2"
                                        onClick={(e) => {
                                            e.stopPropagation();
                                            selectCheckbox(file);
                                        }}
                                    >
                                        <input
                                            type="checkbox"
                                            checked={!!checkboxStates[file.id]}
                                            onChange={() => {
                                            }}
                                            className="h-4 w-4 rounded border-gray-600 bg-gray-700 text-blue-500 focus:ring-blue-500 focus:ring-offset-gray-900"
                                        />
                                    </div>
                                </div>

                                {/* File Icon */}
                                {file.is_dir === 0 &&
                                    <div
                                        className="flex cursor-pointer justify-center items-center h-full transition-transform duration-200 h-[220px]"
                                        onClick={() => handleFileClick(file)}
                                    >
                                        {file.has_thumbnail && !file.filename.endsWith('.svg') ? (
                                                <img
                                                    src={`/fetch-thumb/${file.hash}`}
                                                    alt="Thumbnail"
                                                />
                                            )
                                            : (
                                                <File
                                                    className="text-gray-400 group-hover:text-gray-300 "
                                                    size={120}
                                                />
                                            )
                                        }
                                    </div>
                                }

                                {file.is_dir === 1 &&
                                    <div
                                        className="flex cursor-pointer justify-center pb-3 transition-transform duration-200  h-[220px]"
                                    >
                                        <Link
                                            href={'/drive' + (file.public_path ? ('/' + file.public_path) : '') + '/' + file.filename}
                                            className={`flex items-center `} preserveScroll
                                        >
                                            <Folder className={`mr-2 text-yellow-600`} size={120}/>
                                        </Link>
                                    </div>
                                }
                            </div>

                            {/* Action Buttons */}
                            <div
                                className="flex justify-between absolute bottom-0 left-1/2 transform -translate-x-1/2 w-4/5 mb-2 opacity-60 group-hover:flex hidden ">
                                <div className="flex-1">
                                    <DeleteButton
                                        classes=" bg-red-500/10 hover:bg-red-500/20 text-red-500 py-2 px-4 rounded-md transition-colors duration-200"
                                        selectedFiles={selectedFileMap}
                                    />
                                </div>
                                <div className="flex-1">
                                    <DownloadButton
                                        classes="w-full  bg-blue-500/10 hover:bg-blue-500/20 text-blue-500 py-2 px-4 rounded-md transition-colors duration-200"
                                        selectedFiles={selectedFileMap}
                                        token={token}
                                        setStatusMessage={setStatusMessage}
                                    />
                                </div>
                            </div>
                        </div>
                    )
                })}
            </div>
        </div>
    );
};

export default TileViewOne;