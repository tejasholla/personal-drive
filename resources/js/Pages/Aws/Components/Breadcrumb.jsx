import {Link} from "@inertiajs/react";
import {ChevronRight, HomeIcon} from 'lucide-react'

export default function Breadcrumb({path}) {
    let pathArr = path.split('/');
    let rootLink = '/drive';
    let links = [];
    for (let link of pathArr) {
        rootLink += '/' + link;
        links.push({name: link, href: rootLink});
    }


    return (<nav aria-label="Breadcrumb" className="mb-4 ">
        <ol className="flex items-center flex-wrap h-10">
            <li className="flex items-center">
                {path  &&
                    <Link className="p-2 rounded-md inline-flex w-auto bg-gray-700" href='/drive'>
                        <HomeIcon className={`text-gray-500 inline`} size={22}/>
                        <span className={`mx-1`}>Go to base dir</span>
                    </Link>
                }

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
                                  className="text-blue-600 hover:text-blue-800 transition-colors duration-200">
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

