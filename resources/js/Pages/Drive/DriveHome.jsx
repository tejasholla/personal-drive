import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.jsx';
import {Head, router} from '@inertiajs/react';
import FileManager from './FileManager.jsx';
import {useState} from "react";

export default function DriveHome({files, path, token}) {
    console.log('render filemanager ', );
    return (<AuthenticatedLayout
        header={<div className="flex justify-between">
            <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                File Manager
            </h2>
        </div>}
    >
        <Head title="File manager"/>
        <div className="max-w-7xl mx-auto  bg-gray-800 text-gray-200">
            <FileManager
                files={files}
                path={path}
                token={token}
            />
        </div>
    </AuthenticatedLayout>);
}
