import FileItem from './FileItem.jsx';
import FolderItem from './FolderItem.jsx';
import {Link} from "@inertiajs/react";
import {CircleArrowLeft, HomeIcon} from 'lucide-react';
import SearchBar from "./SearchBar.jsx";


const FileList = ({files, onSelect, onSelectFolder, selectedItem, handleSearch, bucketName}) => {
    console.log('bucketName',bucketName);
    return (
        <div className="px-3 py-3 mt-12">
            <div className="  rounded-md overflow-hidden gap-x-2 flex justify-between">
                <div className="p-2 gap-x-2 flex ">
                    <Link className="p-2 rounded-md inline-flex w-auto bg-gray-700"
                          onClick={() => window.history.back()}>
                        <CircleArrowLeft className={`text-gray-500 inline`} size={22}/>
                        <span className={`mx-1`}>Back</span>
                    </Link>
                    <Link className="p-2 rounded-md inline-flex w-auto bg-gray-700" href='/s3dashboard'>
                        <HomeIcon className={`text-gray-500 inline`} size={22}/>
                        <span className={`mx-1`}>All Buckets</span>
                    </Link>
                </div>
                <div className="p-2 gap-x-2 flex ">
                    <SearchBar handleSearch={handleSearch}/>
                </div>

                </div>
                <div className=" rounded-md overflow-hidden ">
                    {files.map((file) => {
                        console.log('file.Key, file.Prefix',file);
                        if (file.is_dir) {
                            return <FolderItem
                                key={file.id}
                                file={file}
                                onSelect={onSelectFolder}
                                isSelected={selectedItem && selectedItem.id === file.id}
                                bucketName={bucketName}
                            />
                        } else {
                            return <FileItem
                                key={file.id}
                                file={file}
                                onSelect={onSelect}
                                isSelected={selectedItem && selectedItem.id === file.id}
                                bucketName={bucketName}

                            />
                        }
                    })}
                </div>
            </div>
            );
            };

            export default FileList;