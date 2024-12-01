import {File} from 'lucide-react';

export default function FileItem ({file, onSelect, isSelected}){

    return (
        <div
            className={`hover:bg-gray-600 flex items-center p-2 cursor-pointer hover:bg-gray-100 ${
                isSelected ? 'bg-blue-100' : ''
            }`}
            onClick={() => onSelect(file)}
        >
            <File className={`mr-2 text-gray-300`} size={20} />
            <span>{file.fileName}</span>
        </div>
    );
}