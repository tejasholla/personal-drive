import FolderItem from "@/Pages/Aws/Components/FolderItem.jsx";
import FileItem from "@/Pages/Aws/Components/FileItem.jsx";
import { memo } from 'react';
import { useNavigate } from 'react-router-dom';


const FileFolderRows = memo(({files,path, isSearch, selectFile}) => {
    console.log(' filefolder rows', files,path, isSearch, );
    const navigate = useNavigate();


    return (
        <div className=" rounded-md overflow-hidden p-2 ">
            <table className="w-full ">
                <thead>
                <tr className="text-left text-gray-400 border-b border-b-gray-600">
                    <th className="p-2 px-6 w-20 text-center">Select</th>
                    <th className="p-2 px-4">Name</th>
                    <th className="p-2  px-4">Size</th>
                </tr>
                </thead>
                <tbody className="border-spacing-y-4">
                {(path || isSearch) && <tr className="cursor-pointer hover:bg-gray-700 " title="Go back">
                    <td className="p-4 px-9" colSpan={3} onClick={() => navigate(-1)}>..
                    </td>
                </tr>}
                {files.map((file) => (<tr key={file.id} className="cursor-pointer hover:bg-gray-700">
                    <td title="Select" className="px-1 hover:bg-gray-900 justify-center text-center"
                        onClick={(e) => {
                            selectFile(file);
                            const input = e.target === 'input' ? e.target : e.target.querySelector('input');
                            if (input) {
                                input.checked = !input.checked;
                            }
                        }}>
                        <input type="checkbox" checked={false}/>
                    </td>
                    <td className="p-4">
                        {file.is_dir ? <FolderItem
                            file={file}
                            // isSelected={selectedItem && selectedItem.id === file.id}
                            isSearch={isSearch}
                        /> : <FileItem
                            file={file}
                            isSearch={isSearch}
                        />}
                    </td>
                    <td className="p-4">
                        {file.size}
                    </td>
                </tr>))}
                </tbody>
            </table>
        </div>
    );
});

export default FileFolderRows;