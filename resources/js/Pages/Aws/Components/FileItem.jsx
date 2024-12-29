import { File } from 'lucide-react';

export default function FileItem({ file, isSearch }) {
    return (
        <div
            className={`p-4 flex items-center `}
        >
            <File className={`mr-2 text-gray-300`} size={20} />
            <span>{(isSearch ? file.public_path + '/' : '') + file.filename}</span>
        </div>
    );
}