import {DownloadIcon} from "lucide-react";
import {router} from "@inertiajs/react";

const DownloadButton = ({setSelectedFiles, selectedFiles, classes, setStatusMessage, statusMessage}) => {
    const handleDownload = async () => {
        try {
            setStatusMessage('Downloading...');
            // setIsLoading(true);
            const response = await axios({
                url: '/download-files',
                method: 'POST',
                responseType: 'blob',
                data: {
                    fileList: Object.fromEntries(selectedFiles)
                }
            });

            // Create blob link to download
            const blob = new Blob([response.data], {
                type: response.headers['content-type']
            });
            const url = window.URL.createObjectURL(blob);
            // Create temporary link and trigger download
            const link = document.createElement('a');
            link.href = url;
            // Try to get filename from response headers
            const contentDisposition = response.headers['content-disposition'];
            const fileName = contentDisposition
                ? contentDisposition.split('filename=')[1].replace(/['"]/g, '')
                : 'download';
            link.setAttribute('download', fileName);
            document.body.appendChild(link);
            link.click();
            // Cleanup
            link.parentNode.removeChild(link);
            window.URL.revokeObjectURL(url);
        } finally {
            setStatusMessage('');
            setSelectedFiles(new Map());
        }
    };
    return (
        <button
            disabled={statusMessage}
            onClick={handleDownload}
            className={`p-2 rounded-md flex items-center justify-center w-auto bg-green-800 ${classes} ${statusMessage ? 'bg-gray-700 text-green-100 cursor-not-allowed' : 'bg-blue-700 hover:bg-blue-600 active:bg-blue-700'}`}
        >
            {statusMessage ? (
                    <div className="w-5 h-5 border-t-2 border-blue-300 border-solid rounded-full animate-spin"></div>
                ) :
                <>
                    <DownloadIcon className="text-center text-green-500 inline" size={22}/> {!classes &&
                    <span className="mx-1 ">Download</span>}
                </>
            }

        </button>

    );
};

export default DownloadButton;