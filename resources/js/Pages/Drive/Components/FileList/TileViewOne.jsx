import React, {useEffect} from 'react';
import {Link, router} from '@inertiajs/react'
import SortIcon from "../../Svgs/SortIcon.jsx";
import FileTileViewCard from "@/Pages/Drive/Components/FileList/FileTileViewCard.jsx";


const TileViewOne = ({
                         filesCopy,
                         token,
                         setStatusMessage,
                         handleFileClick,
                         isSearch,
                         sortCol,
                         sortDetails,
                         setFilesCopy,
                         selectedFiles,
                         handlerSelectFile,
                         selectAllToggle,
                         handleSelectAllToggle,
                         setIsShareModalOpen,
                         setFilesToShare
                     }) => {
    console.log('TileViewOne ', )


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
                     onClick={(e) => handleSelectAllToggle(e)}>
                    <input className=" hover:cursor-pointer" type="checkbox" checked={selectAllToggle} readOnly/>
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
                {filesCopy.map((file) => (
                    <FileTileViewCard
                        key={file.id}
                        file={file}
                        isSearch={isSearch}
                        token={token}
                        setStatusMessage={setStatusMessage}
                        handleFileClick={handleFileClick}
                        isSelected={selectedFiles.has(file.id)}
                        handlerSelectFile={handlerSelectFile}
                        setIsShareModalOpen={setIsShareModalOpen}
                        setFilesToShare={setFilesToShare}
                    />
                ))}
            </div>
        </div>
    );
};

export default TileViewOne;