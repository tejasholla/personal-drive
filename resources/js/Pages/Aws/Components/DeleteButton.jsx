import {DeleteIcon} from "lucide-react";


const DeleteButton = ({handleDeleteFiles,  selectedFiles, classes}) => {

    async function deleteFilesComponentHandler() {
        return await axios.post('/delete-files', {
            fileList: JSON.stringify(Object.fromEntries(selectedFiles))
        });
    }

    // console.log('selectedFiles ', selectedFiles);
    return (<button className={`p-2 rounded-md flex items-center w-auto bg-red-950  ${classes}`} onClick={() => handleDeleteFiles(deleteFilesComponentHandler)}>
        <DeleteIcon className={`text-red-500 inline`} size={22}/>
        {!classes && <span className={`mx-1`}>Delete</span>}
    </button>);
};

export default DeleteButton;