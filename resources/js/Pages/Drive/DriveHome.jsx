import Header from '@/Pages/Drive/Layouts/Header.jsx';
import FileBrowserSection from "@/Pages/Drive/Components/FileBrowserSection.jsx";

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
