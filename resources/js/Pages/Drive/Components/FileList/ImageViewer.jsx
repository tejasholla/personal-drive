
const ImageViewer = ({ id }) => {
    return (
            <img className="max-h-screen  object-contain"
                src={`/fetch-file/${id}`} // Dynamically load the file
                alt="Selected File"
            />
    );
};

export default ImageViewer;

