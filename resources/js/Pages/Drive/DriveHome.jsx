import Header from '@/Pages/Drive/Layouts/Header.jsx';
import AlertBox from "@/Pages/Drive/Components/AlertBox.jsx";
import DownloadButton from "@/Pages/Drive/Components/DownloadButton.jsx";
import ShowShareModalButton from "@/Pages/Drive/Components/Shares/ShowShareModalButton.jsx";
import ShareModal from "@/Pages/Drive/Components/Shares/ShareModal.jsx";
import DeleteButton from "@/Pages/Drive/Components/DeleteButton.jsx";
import UploadMenu from "@/Pages/Drive/Components/UploadMenu.jsx";
import FileBrowserSection from "@/Pages/Drive/Components/FileBrowserSection.jsx";
import useSelectionUtil from "@/Pages/Drive/Hooks/useSelectionutil.jsx";
import useSearchUtil from "@/Pages/Drive/Hooks/useSearchUtil.jsx";
import {useEffect, useState} from "react";

export default function DriveHome({files, path, token}) {
    console.log('render filemanager ',);


    return (
        <>
            <Header />
            <div className="max-w-7xl mx-auto  bg-gray-800 text-gray-200">
                <div className="p-5">
                    <div className="rounded-md gap-x-2 flex items-start mb-2  justify-between">

                        <div className="p-2 gap-x-2 flex  text-gray-200">

                        </div>
                    </div>
                    <FileBrowserSection files={files} path={path} token={token}
                                        isAdmin={true} />
                </div>
            </div>
        </>
    )
}
