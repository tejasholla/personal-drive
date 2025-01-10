import Header from "@/Pages/Drive/Layouts/Header.jsx";
import {router} from "@inertiajs/react";
import AlertBox from "@/Pages/Drive/Components/AlertBox.jsx";
import Button from "../Components/Generic/Button.jsx"
import {DeleteIcon, PauseIcon, PlayIcon} from "lucide-react";


export default function AllShares({shares}) {
    console.log(' shares ', shares);


    function handlePause(id) {
        router.post('/pause-share', {id: id}, {
            preserveState: true,
            preserveScroll: true,
            only: ['shares', 'flash'],
            onFinish: () => {
            }
        });
    }

    function handleDelete(id) {
        router.post('/delete-share', {id: id}, {
            preserveState: true,
            preserveScroll: true,
            only: ['shares', 'flash'],
            onFinish: () => {
            }
        });
    }

    return (
        <>
        <Header/>
        <div className="p-4 space-y-4 max-w-7xl mx-auto dark:text-gray-200">
            <h2 className="text-center text-5xl my-12 mb-32">All Live Shares</h2>
            <main className="mx-auto max-w-7xl">
                <AlertBox/>
                <div className="">
                    <table className="w-full bg-white dark:bg-gray-800 text-left">
                        <thead>
                        <tr>
                            <th className="py-2 px-4 border-b-2 border-gray-700 ">Created</th>
                            <th className="py-2 px-4 border-b-2 border-gray-700 ">Share
                                Details
                            </th>
                            <th className="py-2 px-4 border-b-2 border-gray-700 ">Has Password</th>
                            <th className="py-2 px-4 border-b-2 border-gray-700 ">Expiring on
                            </th>
                            <th className="py-2 px-4 border-b-2 border-gray-700 ">Enabled</th>
                            <th className="py-2 px-4 border-b-2 border-gray-700 ">Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        {shares.map((share) => (
                            <tr key={share.id} className={`${share.enabled ? 'bg-green-800/50' : 'bg-red-800/50'}`}>
                                <td className="py-2 px-4 border-b border-gray-700 ">{new Date(share.created_at).toLocaleDateString()}</td>
                                <td className="py-2 px-4 border-b border-gray-700 flex gap-y-2 flex-col max-w-[500px]">
                                    {window.location.hostname + '/shared/' + share.slug}
                                    <div>{share.shared_files.length} Files: {share.shared_files.slice(0, 1).map(file => file.local_file.filename).join(' | ')} {share.shared_files.length > 1 ? '...' : ''}</div>
                                </td>
                                <td className="py-2 px-4 border-b border-gray-700 ">{share.password ? 'Yes' : 'No'}</td>
                                <td className="py-2 px-4 border-b border-gray-700 ">
                                    {share.expiry_time}
                                </td>
                                <td className="py-2 px-4 border-b border-gray-700 text-green-200  ">
                                    <Button
                                        onClick={() => {
                                            handlePause(share.id)
                                        }}
                                        classes={`inline-flex ${share.enabled ? 'text-red-200 bg-red-300/30' : 'bg-blue-500/80'} hover:bg-blue-700 active:bg-blue-700 '} `}
                                    >
                                        {share.enabled ?
                                            <span><PauseIcon
                                                className="text-red-300 inline"/>Pause</span> :
                                            <span><PlayIcon className="text-green-200 inline"/> Resume </span>}
                                    </Button>
                                </td>
                                <td className="py-2 px-4 border-b border-gray-700 text-red-200">
                                    <Button
                                        onClick={() => {
                                            handleDelete(share.id)
                                        }}
                                        classes={`inline-flex bg-red-800 hover:bg-red-600 active:bg-red-700 '} `}
                                    >
                                        <span><DeleteIcon className="text-red-300 inline"/> Delete </span>
                                    </Button>
                                </td>
                            </tr>
                        ))}
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
</>)
    ;
}