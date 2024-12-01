import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.jsx';
import {Head} from '@inertiajs/react';
import FileList from './FileList.jsx';
import SearchBar from './SearchBar.jsx';
import {useEffect, useState} from "react";

export default function FileManager({files, bucketName}) {
    const [currentPath, setCurrentPath] = useState('/');
    const [selectedItem, setSelectedItem] = useState(null);
    const [currentFiles, setCurrentFiles] = useState([]);


    useEffect(() => {
        if (currentFiles !== files) {
            setCurrentFiles(files);
        }
    }, [files]);

    const handleSelect = (item) => {
        setSelectedItem(item);
        if (item.type === 'folder') {
            setCurrentPath(`${currentPath}${item.name}/`);
        }
    };

    const handleSearch = async (e, searchText) => {
        e.preventDefault();
        const response = await axios.post('/search-files', {query: searchText});
        // console.log(response.data.files);
        if (response && response.data.files) {
            setCurrentFiles(response.data.files);
        }
    }

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                        File Manager
                    </h2>
                </div>
            }
        >
            <Head title="File manager"/>
            <div className="max-w-7xl mx-auto  bg-gray-800 text-gray-200">
                <FileList
                    files={currentFiles}
                    onSelect={handleSelect}
                    selectedItem={selectedItem}
                    handleSearch={handleSearch}
                    bucketName={bucketName}
                />
            </div>
        </AuthenticatedLayout>
    );
}
