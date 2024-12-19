import {File, Folder, Ruler} from 'lucide-react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.jsx";
import {Head, Link} from "@inertiajs/react";
import RefreshButton from "@/Pages/Aws/Components/RefreshButton.jsx";
import {useEffect, useState} from "react";

export default function Dashboard({bucketStats = {}, error=""}) {
    console.log('bucketStats', bucketStats);
    const [currentBucketStats, setCurrentBucketStats] = useState({});
    useEffect(() => {
        setCurrentBucketStats(bucketStats);
    }, [bucketStats]);
    async function  handleRefreshBucketButton(bucketName){
        try {
            const response = await axios.post('/refresh-bucket-stats', {bucketName});
            bucketStats[bucketName] = response.data;
            setCurrentBucketStats({...bucketStats});
        } catch (error) {
            console.error('Error refreshing bucket:', error);
        }
        return 'handle refresh return';
    }

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                       S3 Dashboard and stats
                    </h2>
                </div>
            }
        >
            <Head title="S3 Dashboard"/>
            <div className="p-4 space-y-4 max-w-7xl mx-auto dark:text-gray-200">
                <h2 className="text-center text-4xl my-12">All Buckets</h2>
                {Object.entries(currentBucketStats).map(([bucketName, {totalSize, totalNumFiles}]) => (
                    <Link href={`/bucket/${bucketName}`} key={bucketName} className="p-4 border rounded shadow-md block" >
                        <div className="flex items-center space-x-2 mb-2">
                            <Folder className="w-5 h-5 text-gray-600"/>
                            <h2 className="text-lg font-semibold">{bucketName}</h2>
                        </div>
                        <div className="ml-6 space-y-1">
                            <div className="flex items-center space-x-2">
                                <Ruler className="w-4 h-4 text-gray-500"/>
                                <p className="text-sm">Total Size: {totalSize}</p>
                            </div>
                            <div className="flex items-center space-x-2">
                                <File className="w-4 h-4 text-gray-500"/>
                                <p className="text-sm">Total Files: {totalNumFiles}</p>
                            </div>
                            <div className="flex items-center space-x-2">
                                <RefreshButton handleRefreshBucketButton={() => handleRefreshBucketButton(bucketName)} />

                            </div>
                        </div>
                    </Link>
                ))}
                {error && <div className="bg-red-700 border border-red-400 text-red-200 p-4 rounded-md">
                    <h2 className="text-center text-3xl font-bold">{error}</h2>
                    <div className="text-xl mt-4">Please configure S3 properly:</div>
                    <div className="mt-2">
                        <ul className="list-disc ml-5">
                            <li>AWS Access ID</li>
                            <li>Region</li>
                            <li>Secret Key</li>
                        </ul>
                        <p className="mt-4">Currently, these need to be set manually in the <code
                            className="italic">.env</code> file or as  environment variables.</p>
                    </div>
                </div>
                }
            </div>
        </AuthenticatedLayout>


    );
}