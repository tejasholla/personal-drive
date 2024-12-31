
export default function S3ErrorPage ({error}){
    return (
        <div className="bg-red-700 border border-red-400 text-red-200 p-4 rounded-md">
            <h2 className="text-center text-3xl font-bold">{error}</h2>
            <div className="text-xl mt-4">Please configure S3 properly:</div>
            <div className="mt-2">
                <ul className="list-disc ml-5">
                    <li>AWS Access ID</li>
                    <li>Region</li>
                    <li>Secret Key</li>
                </ul>
                <p className="mt-4">Currently, these need to be set manually in the <code
                    className="italic">.env</code> file or as environment variables.</p>
            </div>
        </div>
    );
}