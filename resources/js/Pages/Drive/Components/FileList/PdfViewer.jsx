import { Document, Page, pdfjs } from 'react-pdf';
import {useState} from "react";
pdfjs.GlobalWorkerOptions.workerSrc = new URL(
    'pdfjs-dist/build/pdf.worker.min.mjs',
    import.meta.url,
).toString();
import "react-pdf/dist/esm/Page/AnnotationLayer.css";
import "react-pdf/dist/esm/Page/TextLayer.css";

const PdfViewer = ({ id, slug }) => {
    const [numPages, setNumPages] = useState(0)
    const [pageNumber, setPageNumber]  = useState(1)
    let src = '/fetch-file/' + id ;
    src += slug ? '/' + slug : ''

    function onDocumentLoadSuccess({ numPages }) {
        setNumPages(numPages);
    }
    return (
        <div className="mx-auto w-full ">

            <p>
                Page {pageNumber} of {numPages}
            </p>
            <Document
                file={src} onLoadSuccess={onDocumentLoadSuccess}
                className="text-gray-800  bg-blue-600 w-full "
                onLoadError={(error) => console.error('Error loading PDF:', error)}
            >
                <Page pageNumber={pageNumber} width="1000" />
            </Document>
        </div>
    );
};

export default PdfViewer;