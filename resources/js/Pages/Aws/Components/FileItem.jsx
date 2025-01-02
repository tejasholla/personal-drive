import {File} from 'lucide-react';
import DownloadButton from "./DownloadButton.jsx";
import DeleteButton from "@/Pages/Aws/Components/DeleteButton.jsx";
import  Modal  from './Modal.jsx'
import {useState} from "react";

export default function FileItem({file, isSearch, token, deleteFiles, setStatusMessage, handleFileClick}) {
    let currentFile = new Map();
    currentFile.set(file.id, file.is_dir);

    return (
        <div
            className={` flex items-center  hover:bg-gray-900 justify-between`}
        >

            <div className="flex p-4 w-full" onClick={(e) => handleFileClick(file)}>
                <File className={`mr-2 text-gray-300`} size={20}/>
                <span>{(isSearch ? file.public_path + '/' : '') + file.filename}</span>
            </div>
            <div className="flex ">
                <DeleteButton classes="hidden group-hover:block mr-2  z-10" deleteFiles={deleteFiles} selectedFiles={new Map([[file.id, 0]])}/>

                <DownloadButton  classes="hidden group-hover:block mr-2" selectedFiles={new Map([[file.id, 0]])}
                                token={token} setStatusMessage={setStatusMessage}
                />
            </div>
        </div>
    );
}