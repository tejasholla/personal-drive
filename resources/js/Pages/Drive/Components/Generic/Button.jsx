import React from 'react';

const Button = ({ onClick, classes, children, ...props }) => {
    return (
        <button className={`p-2 rounded-md flex items-center ${classes}`} onClick={onClick} {...props}>
            {children}
        </button>
    );
};

export default Button;