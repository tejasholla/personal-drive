import {Folder} from 'lucide-react';
import {Link} from '@inertiajs/react'
import DownloadButton from "./DownloadButton.jsx";
import DeleteButton from "@/Pages/Aws/Components/DeleteButton.jsx";

export default function FolderItem({file, isSelected, isSearch, token, handleDeleteFiles, setStatusMessage}) {
    console.log('file in folderitem ', file)

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
                <DeleteButton classes="hidden group-hover:block mr-2  z-10" handleDeleteFiles={handleDeleteFiles}
                              selectedFiles={new Map([[file.id, 0]])}/>
                <DownloadButton classes="hidden  group-hover:block mr-2  z-10"
                                selectedFiles={new Map([[file.id, file.is_dir]])} token={token}
                                setStatusMessage={setStatusMessage}
                />
            </div>
        </div>

    );
}