import { Folder } from 'lucide-react';
import { Link } from '@inertiajs/react'
export default function FileItem({ file, onSelect, isSelected, bucketName, isSearch }) {
    return (
        <Link href={'/bucket/' + bucketName + (file.path ? ('/' + file.path) : '') + '/' + file.fileName}
            className={`p-4 flex items-center  hover:bg-gray-900 ${isSelected ? 'bg-blue-100' : ''
                }`}
        >
            <Folder className={`mr-2 text-yellow-600`} size={20} />
            <span>{(isSearch ? file.path + '/' : '') + file.fileName}</span>
        </Link>
    );
}