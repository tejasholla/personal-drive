
import NavLink from '@/Components/NavLink.jsx';
import {Link, router} from "@inertiajs/react";
import SearchBar from "@/Pages/Drive/Components/SearchBar.jsx";
import useSearchUtil from '@/Pages/Drive/Hooks/useSearchUtil.jsx';

export default function Header({  }) {
    const {handleSearch} = useSearchUtil();
    return (
        <div className=" bg-gray-100 dark:bg-gray-900">
            <nav className=" dark:bg-gray-800">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="flex h-16 justify-between ">
                        <div className="hidden space-x-8 sm:-my-px px-3 sm:flex">
                            <NavLink
                                href={route('drive')}
                                active={route().current('drive')}
                            >
                                Files
                            </NavLink>
                            <NavLink
                                href={route('admin-config')}
                                active={route().current('admin-config')}
                            >
                                Settings
                            </NavLink>
                            <NavLink
                                href={route('all-shares')}
                                active={route().current('all-shares')}
                            >
                                Shares
                            </NavLink>
                        </div>


                        <div className="flex gap-x-2 items-center">
                            <SearchBar handleSearch={handleSearch}/>

                            <div className="">
                                <div className="relative group">
                                    <button
                                        className="py-2.5 peer rounded font-semibold">
                                        <svg className="w-6 h-6 rotate-90"
                                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                                        </svg>
                                    </button>
                                    <div
                                        className="absolute z-20 top-10 translate-x-[-40%] bg-gray-900 min-w-[80px]  peer-focus:visible peer-focus:opacity-100 opacity-0 invisible duration-200 p-1 border border-dimmed border-gray-800 text-center">
                                        <div
                                            className=" cursor-pointer  hover:text-link rounded-md">
                                            <Link
                                                method="post"
                                                href={route('logout')}
                                                as="button"
                                            >
                                                Log Out
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </nav>

        </div>
    );
}
