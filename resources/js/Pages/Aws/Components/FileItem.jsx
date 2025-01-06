import {File} from 'lucide-react';
import DownloadButton from "./DownloadButton.jsx";
import DeleteButton from "@/Pages/Aws/Components/DeleteButton.jsx";
import React from 'react';


const FileItem = React.memo(function FileItem({ file, isSearch, token, setStatusMessage, handleFileClick }) {
    return (
        <div
            className={` flex items-center  hover:bg-gray-900 justify-between`}
        >

            <div className="flex p-4 w-full" onClick={(e) => handleFileClick(file)}>
                <File className={`mr-2 text-gray-300 `} size={20}/>
                <span className="truncate w-4/5">{(isSearch ? file.public_path + '/' : '') + file.filename}</span>
            </div>
            <div className="flex ">
                <DeleteButton classes="hidden group-hover:block mr-2  z-10" selectedFiles={new Set([file.id])}/>

                <DownloadButton  classes="hidden group-hover:block mr-2" selectedFiles={new Set([file.id])}
                                token={token} setStatusMessage={setStatusMessage}
                />
            </div>
        </div>
    );
});

export default FileItem;