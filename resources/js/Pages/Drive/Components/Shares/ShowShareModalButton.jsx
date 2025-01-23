import {Share2Icon, ShareIcon, Trash2Icon} from "lucide-react";
import Button from "@/Pages/Drive/Components/Generic/Button.jsx";


const ShowShareModalButton = ({setIsShareModalOpen, classes = '', setFilesToShare, filesToShare}) => {
    function handleShareButton(){
        setIsShareModalOpen(true);
        setFilesToShare(filesToShare);
    }
    return (
        <Button classes={`border border-blue-700 text-blue-200 hover:bg-blue-950 active:bg-gray-900 ${classes}`} onClick={() => handleShareButton()}>
            <Share2Icon className={`text-blue-500 inline`} size={22}/>
            {!classes && <span className={`mx-1`}>Share</span>}
        </Button>
    );
};

export default ShowShareModalButton;