import {Link} from "@inertiajs/react";
import {ChevronRight, HomeIcon} from 'lucide-react'

export default function Breadcrumb({path, isAdmin}) {
    let rootLink = isAdmin ? '/drive' : '/shared';
    let links = [];
    if (path) {
        let pathArr = path.split('/');
        console.log('pathArr ', pathArr, path);
        pathArr.shift();
        pathArr.shift();
        console.log('pathArr ', pathArr);
        for (let link of pathArr) {
            rootLink += '/' + link;
            links.push({name: link, href: rootLink});
        }
    }


    return (<nav aria-label="Breadcrumb" className="mb-4 ">
        <ol className="flex  flex-wrap h-10">
            <li className="flex items-center">

                    <Link className="hover:bg-gray-600 p-2 rounded-md inline-flex w-auto bg-gray-700" href='/drive' preserveScroll>
                        <HomeIcon className={`text-gray-500 inline`} size={22}/>
                        <span className={`mx-1`}>Base Folder</span>
                    </Link>

                {links.length > 0 && path && (<ChevronRight className="w-4 h-4 text-gray-400 mx-2" aria-hidden="true"/>)}
            </li>
            {links.map((link, index) =>
                (<li key={index} className="flex items-center">
                    {index === links.length - 1 ?
                        (<span className="text-gray-400 font-medium" aria-current="page">
                            {link.name}
                          </span>) :
                        (<>
                            <Link href={link.href}
                                  className="text-blue-600 hover:text-blue-800 transition-colors duration-200" preserveScroll>
                                {link.name}
                            </Link>
                            <ChevronRight className="w-4 h-4 text-gray-400 mx-2" aria-hidden="true"/>
                        </>)
                    }
                </li>)
            )}
        </ol>
    </nav>)
}

