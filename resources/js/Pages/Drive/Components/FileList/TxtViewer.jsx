import React, {useEffect, useState} from 'react';
import axios from 'axios';

const TxtViewer = ({id, slug}) => {
    const [content, setContent] = useState('');
    const fetchTextFile = async (src) => {
        try {
            const response = await axios.get(src);
            console.log('response response', response);
            setContent(response.data || '');
        } catch (err) {
        }
    };

    useEffect(() => {
        let src = '/fetch-file/' + id;
        src += slug ? '/' + slug : '';
        fetchTextFile(src);
    }, [id]);

    return (
        <div className="">
            <pre className="w-[70vw]">
                {content}
            </pre>
        </div>
    );
};

export default TxtViewer;