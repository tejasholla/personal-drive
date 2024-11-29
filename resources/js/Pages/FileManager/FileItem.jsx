import {File, Folder} from 'lucide-react';

export default function FileItem ({file, onSelect, isSelected}){
    const Icon = file.is_dir ? Folder : File;
    return (
        <div
            className={`flex items-center p-2 cursor-pointer hover:bg-gray-100 ${
                isSelected ? 'bg-blue-100' : ''
            }`}
            onClick={() => onSelect(file)}
        >
            <Icon className={`mr-2 ${file.is_dir ? 'text-yellow-500' : 'text-gray-500'}`} size={20} />
            <span>{file.name}</span>
        </div>
    );
}