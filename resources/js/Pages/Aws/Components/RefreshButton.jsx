'use client'

import { useState } from 'react'
import axios from 'axios'

export default function RefreshButton({handleRefreshBucketButton}) {
    const [isLoading, setIsLoading] = useState(false)

    const handleClick = async (e) => {
        e.preventDefault();
        setIsLoading(true)
        const f = await handleRefreshBucketButton();
        setIsLoading(false);
    }

    return (
        <button
            onClick={handleClick}
            disabled={isLoading}
            className={`rounded-md text-sm flex items-center justify-center transition-colors duration-200 w-40 py-1
                        ${isLoading
                                ? 'bg-gray-700 text-green-100 cursor-not-allowed'
                                : 'bg-blue-900 text-white hover:bg-blue-600 active:bg-blue-700'
                            }
                      `}
        >
            {isLoading ? (
                <div className="w-5 h-5 border-t-2 border-blue-300 border-solid rounded-full animate-spin"></div>
            ) : (
                'Refresh Bucket Stats'
            )}
        </button>
    )
}
