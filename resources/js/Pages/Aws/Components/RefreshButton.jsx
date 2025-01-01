'use client'

import {useEffect, useState} from 'react'
import {usePage} from "@inertiajs/react";

export default function RefreshButton({handleRefreshBucketButton}) {
    // console.log('render refresh button');
    const {flash} = usePage().props

    const [isLoading, setIsLoading] = useState(false)
    const handleClick = async (e) => {
        e.preventDefault();
        setIsLoading(true)
        await handleRefreshBucketButton(() => {
            setIsLoading(false);
            console.log('setIsLoading');
        });
    }

    return (<div className='relative  m-0 p-0'>
        <button
            onClick={handleClick}
            disabled={isLoading}
            className={`rounded-md  p-2 transition-colors duration-200 inline-flex 
                        ${isLoading ? 'bg-gray-700 text-green-100 cursor-not-allowed' : 'bg-blue-700 text-white hover:bg-blue-600 active:bg-blue-700'}
                      `}
        >
            {isLoading ? (<div
                className="w-5 h-5 border-t-2 border-blue-300 border-solid rounded-full animate-spin"></div>) : ('Re-sync')}
        </button>
    </div>)
}
