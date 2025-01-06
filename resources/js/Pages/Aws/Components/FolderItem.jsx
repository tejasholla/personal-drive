import {Folder} from 'lucide-react';
import {Link} from '@inertiajs/react'
import DownloadButton from "./DownloadButton.jsx";
import DeleteButton from "@/Pages/Aws/Components/DeleteButton.jsx";
import React from "react";

const FolderItem =  React.memo(function FolderItem({file, isSelected, isSearch, token,  setStatusMessage}) {
    console.log('Folderitem render')

    return (
        <div
            className={` flex items-center hover:bg-gray-900  justify-between`}
        >
            <Link href={'/drive' + (file.public_path ? ('/' + file.public_path) : '') + '/' + file.filename}
                  className={`p-4  flex items-center w-full  ${isSelected ? 'bg-blue-100' : ''}`}
                  preserveScroll
            >
                <div className="flex  ">
                    <Folder className={`mr-2 text-yellow-600`} size={20}/>
                    <span>
                        {(isSearch ? file.public_path + '/' : '') + file.filename}
                    </span>
                </div>
            </Link>

            <div className="flex gap-x-1">
                <DeleteButton classes="hidden group-hover:block mr-2  z-10"
                              selectedFiles={new Set([file.id])}/>
                <DownloadButton classes="hidden  group-hover:block mr-2  z-10"
                                selectedFiles={new Set([file.id])} token={token}
                                setStatusMessage={setStatusMessage}
                />
            </div>
        </div>

    );
})
export default FolderItem;