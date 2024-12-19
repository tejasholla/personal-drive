export default function SearchBar({handleSearch}) {
    return (
    <div >
        <form className="flex space-x-2 items-center" action="">
            <input type="text" id="searchbox"
                   className="border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700"
                   placeholder="Type to search..."/>
            <button onClick={(e) => handleSearch(e, document.getElementById('searchbox').value)}
                    className="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600 transition duration-300">Search
            </button>
        </form>
    </div>
    );
}
