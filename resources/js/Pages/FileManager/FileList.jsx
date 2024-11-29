import FileItem from './FileItem.jsx';

const FileList = ({ files, onSelect, selectedItem }) => {
    return (
        <div className="border rounded-md overflow-hidden">
            {files.map((file) => (
                <FileItem
                    key={file.id}
                    file={file}
                    onSelect={onSelect}
                    isSelected={selectedItem && selectedItem.id === file.id}
                />
            ))}
        </div>
    );
};

export default FileList;