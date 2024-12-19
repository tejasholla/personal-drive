import React from 'react';
import ReactDOM from 'react-dom';

const Modal = ({ isOpen, onClose, title, children }) => {
    if (!isOpen) return null;
    console.log('onClose', onClose);
    console.log('isopen', isOpen);

    return ReactDOM.createPortal(
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 text-gray-300" onClick={() => onClose(false)}>
            <div className="bg-gray-900 rounded-lg shadow-xl max-w-md w-full mx-4 " onClick={e => e.stopPropagation()}>
                <div className="flex justify-between items-center border-b p-4">
                    <h2 className="text-xl font-semibold">{title}</h2>
                    <button
                        className="text-gray-300 hover:text-gray-700 focus:outline-none"
                        onClick={() => onClose(false)}
                    >
                        <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div className="p-4">
                    {children}
                </div>
            </div>
        </div>,
        document.body
    );
};

export default Modal;

