
const ImageViewer = ({ fileHash }) => {
    return (
            <img className="max-h-screen  object-contain"
                src={`/fetch-file/${fileHash}`} // Dynamically load the file
                alt="Selected File"
            />
    );
};

export default ImageViewer;

