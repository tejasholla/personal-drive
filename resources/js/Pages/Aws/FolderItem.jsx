import {Folder} from 'lucide-react';
import {Link} from '@inertiajs/react'
export default function FileItem ({file, onSelect, isSelected, bucketName}){
    console.log(file);
    return (
        <Link href={'/bucket/'+ bucketName + ( file.path ? ('/' + file.path) : '') + '/' + file.fileName}
            className={`hover:bg-gray-600 block flex items-center p-2 cursor-pointer hover:bg-gray-100 ${
                isSelected ? 'bg-blue-100' : ''
            }`}
        >
            <Folder className={`mr-2 text-yellow-600`} size={20}  />
            <span>{file.fileName }</span>
        </Link>
    );
}