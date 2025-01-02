
const ImageViewer = ({ fileHash }) => {
    return (
        <div className="flex justify-center">
            <img
                src={`/fetch-file/${fileHash}`} // Dynamically load the file
                alt="Selected File"
            />
        </div>
    );
};

export default ImageViewer;

