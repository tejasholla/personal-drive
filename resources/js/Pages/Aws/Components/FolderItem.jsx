import { Folder } from 'lucide-react';
import { Link } from '@inertiajs/react'
export default function FileItem({ file,  isSelected,  isSearch }) {
    return (
        <Link href={'/drive' + (file.public_path ? ('/' + file.public_path) : '') + '/' + file.filename}
            className={`flex items-center  hover:bg-gray-900 ${isSelected ? 'bg-blue-100' : ''
                }`}
        >
            <Folder className={`mr-2 text-yellow-600`} size={20} />
            <span>{(isSearch ? file.public_path + '/' : '') + file.filename}</span>
        </Link>
    );
}