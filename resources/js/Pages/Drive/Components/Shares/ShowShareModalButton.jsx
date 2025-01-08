import {ShareIcon} from "lucide-react";


const ShowShareModalButton = ({setIsShareModalOpen, classes, setFilesToShare, filesToShare}) => {
    function handleShareButton(){
        setIsShareModalOpen(true);
        setFilesToShare(filesToShare);
    }
    return (
        <button className={`p-2 rounded-md flex items-center w-auto bg-red-950  ${classes}`}
                onClick={() => handleShareButton()}>
            <ShareIcon className={`text-blue-500 inline`} size={22}/>
            {!classes && <span className={`mx-1`}>Share</span>}
        </button>);
};

export default ShowShareModalButton;