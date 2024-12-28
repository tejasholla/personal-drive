import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.jsx";
import {Head, router} from "@inertiajs/react";
import {useState} from "react";
import AlertBox from "@/Pages/Aws/Components/AlertBox.jsx";

export default function Dashboard({settings = {}, message = '', status = false}) {
    const [formData, setFormData] = useState({
        storage_path: settings.storage_path,
    })

    function handleChange(e) {
        console.log('handleChange');
        setFormData(oldValues => ({...oldValues, ['storage_path']: e.target.value}))
    }

    function handleSubmit(e) {
        e.preventDefault()
        router.post('/admin-config/update', formData)
    }

    return (<AuthenticatedLayout
        header={<div className="flex justify-between">
            <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Personal Drive Admin Config
            </h2>
        </div>}
    >
        <Head title="Personal Drive Admin Config"/>

        <div className="p-4 space-y-4 max-w-7xl mx-auto dark:text-gray-200">
            <h2 className="text-center text-5xl my-12 mb-32">Admin Settings</h2>
            <main className="mx-auto max-w-7xl ">
                {<AlertBox message={message} type={status ? 'success' : 'warning'}/>}
                <form className="w-[700px] mx-auto bg-blue-900/15 p-12 min-h-[500px] flex flex-col justify-between"
                      onSubmit={handleSubmit}>
                    <div>
                        <div className="mb-4 flex  mx-auto items-center justify-between w-full">
                            <label htmlFor="storage_path"
                                   className="block text-blue-200 text-xl font-bold ">
                                Storage Path:
                            </label>
                            <input
                                type="text"
                                id="storage_path"
                                name="storage_path"
                                value={formData.storage_path}
                                onChange={handleChange}
                                className="w-2/3  text-gray-200 bg-blue-900 border border-blue-900 rounded-md focus:border-indigo-500 focus:ring-indigo-500 "
                            />
                        </div>

                        <div className="flex justify-between">
                            <p className="text-sm text-gray-400 text-right mt-1">Root Path of all files</p>
                            <p className="text-sm text-gray-400 text-right mt-1 font-bold">Changing will NOT move files !! </p>
                        </div>
                    </div>
                    <div className="flex justify-center mt-32">
                        <button
                            className="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 ">
                            Update Settings
                        </button>
                    </div>
                </form>
            </main>
        </div>
    </AuthenticatedLayout>);
}