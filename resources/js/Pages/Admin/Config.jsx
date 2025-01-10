import Header from "@/Pages/Drive/Layouts/Header.jsx";
import {router} from "@inertiajs/react";
import {useState} from "react";
import AlertBox from "@/Pages/Drive/Components/AlertBox.jsx";
import RefreshButton from "@/Pages/Drive/Components/RefreshButton.jsx";

export default function AdminConfig({
                                        storage_path,
                                        php_max_upload_size,
                                        php_post_max_size,
                                        php_max_file_uploads
                                    }) {


    const [formData, setFormData] = useState({
        storage_path: storage_path,
        php_max_upload_size: php_max_upload_size,
        php_post_max_size: php_post_max_size,
        php_max_file_uploads: php_max_file_uploads
    })
    console.log('formData ', formData);

    function handleChange(e) {
        console.log('handleChange');
        setFormData(oldValues => ({...oldValues, [e.target.id]: e.target.value}))
    }

    function handleSubmit(e) {
        e.preventDefault()
        router.post('/admin-config/update', formData, {
            onSuccess: (res) => {
                setFormData({
                    storage_path: res.props.storage_path
                })
            }
        })
    }

    return (
        <>
        <Header/>

        <div className="p-4 space-y-4 max-w-7xl mx-auto dark:text-gray-200">
            <h2 className="text-center text-5xl my-12 mb-32">Admin Settings</h2>
            <main className="mx-auto max-w-7xl ">
                <AlertBox/>

                <div className="w-[700px] mx-auto bg-blue-900/15 p-12 min-h-[500px] flex flex-col gap-y-20 ">

                    <form
                        className="flex flex-col justify-between gap-y-6"
                        onSubmit={handleSubmit}>
                        <div>
                            <div className="m-1 flex flex-col mx-auto items-start gap-y-5 w-full">
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
                                    className="w-full  text-gray-200 bg-blue-900 border border-blue-900 rounded-md focus:border-indigo-500 focus:ring-indigo-500 "
                                />
                            </div>

                            <div className="flex justify-between">
                                <p className="text-sm text-gray-400 text-right mt-1">Root Path of all files</p>
                                <p className="text-sm text-gray-400 text-right mt-1 font-bold">Changing will NOT
                                    move
                                    files !! </p>
                            </div>
                        </div>
                        <div className="flex justify-center mt-1">
                            <button
                                className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 ">
                                Update Settings
                            </button>
                        </div>
                    </form>
                    <div>
                        <p className=" text-blue-200 text-xl font-bold mb-2 ">
                            PHP Upload Size Limits
                        </p>

                        <div className=" mb-0 flex  mx-auto items-baseline gap-x-2 w-full">
                            <p className="  font-bold ">
                                Max upload size:
                            </p>
                            <p className="text-lg text-gray-200 text-right mt-1">
                                {formData.php_max_upload_size}
                            </p>
                        </div>
                        <div className=" flex  mx-auto items-baseline gap-x-2 w-full">
                            <p className="  font-bold ">
                                Post upload size:
                            </p>
                            <p className="text-lg text-gray-200 text-right mt-1">
                                {formData.php_post_max_size}
                            </p>
                        </div>
                        <div className=" flex  mx-auto items-baseline gap-x-2 w-full">
                            <p className="  font-bold ">
                                Max File Uploads:
                            </p>
                            <p className="text-lg text-gray-200 text-right mt-1">
                                {formData.php_max_file_uploads}
                            </p>
                        </div>

                        <p className="text-lg text-blue-200 mt-10 mb-5 font-bold">Ensure PHP settings are

                            configured correctly</p>
                        <div className="flex flex-col ">
                            If you are on a VPS and using php-fpm. Edit the www.conf file. restart php-fpm
                            <pre className="mt-1 mb-5 text-sm text-gray-400">
                                {`php_value[upload_max_filesize] = 64M
php_value[post_max_size] = 64M
php_value[php_max_file_uploads] = 10000`}
                            </pre>
                            If you are on a VPS and can edit the ini file. Set these 2 variables
                            <pre className="mt-1 mb-5 text-sm text-gray-400">
                                {`upload_max_filesize = 1G
post_max_size = 1G
max_file_uploads = 10000`}
                            </pre>

                            If you are running apache, try editing the .htaccess file in /public directory
                            <pre className="mt-1 mb-5 text-sm text-gray-400">
                                {`php_value upload_max_filesize 64M
php_value post_max_size 64M
php_value max_file_uploads 10000`}
                            </pre>
                        </div>
                    </div>

                    <div className="  ">

                        <h2 className=" text-blue-200 text-xl font-bold mb-2 ">Refresh Database and cancel all
                            Shares </h2>
                        <p className="mb-4">This is a 'reset' option, and will reindex all files. Remove all
                            shares</p>
                        <RefreshButton/>

                    </div>
                </div>


            </main>
        </div>
</>
)
    ;
}