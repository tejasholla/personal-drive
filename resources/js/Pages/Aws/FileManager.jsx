import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.jsx';
import {Head, router} from '@inertiajs/react';
import FileList from './FileList.jsx';
import {useEffect, useState} from "react";

export default function FileManager({files, path, token}) {
    console.log('render filemanager ', files);
    const [currentFiles, setCurrentFiles] = useState([...files]);
    const [isSearch, setIsSearch] = useState(false);

    const handleSearch = async (e, searchText) => {
        e.preventDefault();
        router.post('/search-files', {query: searchText}, {
            onSuccess: () => {
                setIsSearch(true);
            }
        });
    }

    return (<AuthenticatedLayout
        header={<div className="flex justify-between">
            <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                File Manager
            </h2>
        </div>}
    >
        <Head title="File manager"/>
        <div className="max-w-7xl mx-auto  bg-gray-800 text-gray-200">
            <FileList
                files={files}
                handleSearch={handleSearch}
                isSearch={isSearch}
                path={path}
                token={token}
            />
        </div>
    </AuthenticatedLayout>);
}
