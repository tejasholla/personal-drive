import {DeleteIcon} from "lucide-react";
import {router} from "@inertiajs/react";


const DeleteButton = ({setSelectedFiles, selectedFiles, classes}) => {

    async function deleteFilesComponentHandler() {
        router.post('/delete-files', {
            fileList: Object.fromEntries(selectedFiles)
        }, {
            preserveState: true,
            preserveScroll: true,
            only: ['files', 'flash'],
            onFinish: () => {
                console.log('on finish delteion');
                setSelectedFiles(new Map());
            }
        });
    }

    // console.log('selectedFiles ', selectedFiles);
    return (<button className={`p-2 rounded-md flex items-center w-auto bg-red-950  ${classes}`} onClick={() => deleteFilesComponentHandler()}>
        <DeleteIcon className={`text-red-500 inline`} size={22}/>
        {!classes && <span className={`mx-1`}>Delete</span>}
    </button>);
};

export default DeleteButton;