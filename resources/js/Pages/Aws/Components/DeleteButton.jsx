import {DeleteIcon} from "lucide-react";


const DownloadButton = ({deleteFiles,  selectedFiles, classes}) => {

    async function deleteFilesComponentHandler() {
        return await axios.post('/s3/delete-files', {
            fileList: JSON.stringify(Object.fromEntries(selectedFiles))
        });
    }

    // console.log('selectedFiles ', selectedFiles);
    return (<button className={`p-2 rounded-md flex items-center w-auto bg-red-950  ${classes}`} onClick={() => deleteFiles(deleteFilesComponentHandler)}>
        <DeleteIcon className={`text-red-500 inline`} size={22}/>
        {!classes && <span className={`mx-1 text-gray-200`}>Delete</span>}
    </button>);
};

export default DownloadButton;