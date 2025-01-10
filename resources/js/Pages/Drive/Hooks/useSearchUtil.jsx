
import {useState} from "react";
import {router} from "@inertiajs/react";

function useSearchUtil( ) {


    const [isSearch, setIsSearch] = useState(false);

    async function handleSearch(e, searchText) {
        e.preventDefault();
        router.post('/search-files', {query: searchText}, {
            onSuccess: () => {
                setIsSearch(true);
            }
        });
    }


    return {isSearch, setIsSearch, handleSearch}

}

export default useSearchUtil;