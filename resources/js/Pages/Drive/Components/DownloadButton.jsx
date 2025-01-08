import {DownloadIcon} from "lucide-react";
import Button from "./Generic/Button.jsx"

const DownloadButton = ({setSelectedFiles, selectedFiles, classes, setStatusMessage, statusMessage, setSelectAllToggle}) => {
    const handleDownload = async () => {
        let response = {};
        try {
            setStatusMessage('Downloading...');
            // setIsLoading(true);
            response = await axios({
                url: '/download-files',
                method: 'POST',
                responseType: 'blob',
                data: {
                    fileList: Array.from(selectedFiles)
                }
            });
        }
        finally {
            setStatusMessage('');
            setSelectedFiles?.(new Set());
            setSelectAllToggle?.(false);
        }
        // Check if the response is JSON
        const contentType = response.headers['content-type'];
        if (contentType && contentType.includes('application/json')) {
            // Convert blob to JSON
            const text = await response.data.text();
            const jsonResponse = JSON.parse(text);

            if (!jsonResponse.status && jsonResponse.message) {
                setStatusMessage('Download failed ' + jsonResponse.message);
                console.error(jsonResponse.message); // Handle the error message
            }
        } else {

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
        }

    };
    return (
        <Button classes={`bg-green-800 ${classes} ${statusMessage ? 'bg-gray-700 text-green-100 cursor-not-allowed' : 'bg-blue-700 hover:bg-blue-600 active:bg-blue-700'}`}
                disabled={statusMessage}
                onClick={handleDownload}
        >
            {statusMessage ? (
                    <div className="w-5 h-5 border-t-2 border-blue-300 border-solid rounded-full animate-spin"></div>
                ) :
                <>
                    <DownloadIcon className="text-center text-green-500 inline" size={22}/> {!classes &&
                    <span className="mx-1 ">Download</span>}
                </>
            }
        </Button>
    );
};

export default DownloadButton;