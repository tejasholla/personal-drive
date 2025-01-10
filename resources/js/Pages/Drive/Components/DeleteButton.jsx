import {DeleteIcon, Trash2Icon} from "lucide-react";
import {router} from "@inertiajs/react";
import Button from "./Generic/Button.jsx"

const DeleteButton = ({setSelectedFiles, selectedFiles, classes, setSelectAllToggle}) => {
    async function deleteFilesComponentHandler() {
        router.post('/delete-files', {
            fileList: Array.from(selectedFiles)
        }, {
            preserveState: true,
            preserveScroll: true,
            only: ['files', 'flash'],
            onFinish: () => {
                setSelectedFiles?.(new Set());
                setSelectAllToggle?.(false);
            }
        });
    }

    return (
        <Button classes={`bg-red-950 ${classes}`} onClick={deleteFilesComponentHandler}>
            <Trash2Icon className={`text-red-500 inline`} size={22} />
            {!classes && <span className={`mx-1`}>Delete</span>}
        </Button>
    );
};

export default DeleteButton;