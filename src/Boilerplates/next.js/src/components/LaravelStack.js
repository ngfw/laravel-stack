'use client'

import { useEffect } from 'react'
import { confetti } from '@tsparticles/confetti'

const LaravelStack = () => {
    useEffect(() => {
        confetti({
            particles: 100,
            angle: 90,
            spread: 50,
            origin: { x: 0.5, y: 0.5 },
        })
    }, [])

    return (
        <div className="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div className="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div className=" z-10 flex-1">
                    <section className="flex justify-center flex-col ">
                        <div className="logos mb-5 flex justify-center pt-8 sm:justify-start sm:pt-0">
                            <svg
                                viewBox="0 0 651 192"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                className="h-16 w-auto text-gray-700 sm:h-20">
                                <g clipPath="url(#clip0)" fill="#EF3B2D">
                                    <path d="M248.032 44.676h-16.466v100.23h47.394v-14.748h-30.928V44.676zM337.091 87.202c-2.101-3.341-5.083-5.965-8.949-7.875-3.865-1.909-7.756-2.864-11.669-2.864-5.062 0-9.69.931-13.89 2.792-4.201 1.861-7.804 4.417-10.811 7.661-3.007 3.246-5.347 6.993-7.016 11.239-1.672 4.249-2.506 8.713-2.506 13.389 0 4.774.834 9.26 2.506 13.459 1.669 4.202 4.009 7.925 7.016 11.169 3.007 3.246 6.609 5.799 10.811 7.66 4.199 1.861 8.828 2.792 13.89 2.792 3.913 0 7.804-.955 11.669-2.863 3.866-1.908 6.849-4.533 8.949-7.875v9.021h15.607V78.182h-15.607v9.02zm-1.431 32.503c-.955 2.578-2.291 4.821-4.009 6.73-1.719 1.91-3.795 3.437-6.229 4.582-2.435 1.146-5.133 1.718-8.091 1.718-2.96 0-5.633-.572-8.019-1.718-2.387-1.146-4.438-2.672-6.156-4.582-1.719-1.909-3.032-4.152-3.938-6.73-.909-2.577-1.36-5.298-1.36-8.161 0-2.864.451-5.585 1.36-8.162.905-2.577 2.219-4.819 3.938-6.729 1.718-1.908 3.77-3.437 6.156-4.582 2.386-1.146 5.059-1.718 8.019-1.718 2.958 0 5.656.572 8.091 1.718 2.434 1.146 4.51 2.674 6.229 4.582 1.718 1.91 3.054 4.152 4.009 6.729.953 2.577 1.432 5.298 1.432 8.162-.001 2.863-.479 5.584-1.432 8.161zM463.954 87.202c-2.101-3.341-5.083-5.965-8.949-7.875-3.865-1.909-7.756-2.864-11.669-2.864-5.062 0-9.69.931-13.89 2.792-4.201 1.861-7.804 4.417-10.811 7.661-3.007 3.246-5.347 6.993-7.016 11.239-1.672 4.249-2.506 8.713-2.506 13.389 0 4.774.834 9.26 2.506 13.459 1.669 4.202 4.009 7.925 7.016 11.169 3.007 3.246 6.609 5.799 10.811 7.66 4.199 1.861 8.828 2.792 13.89 2.792 3.913 0 7.804-.955 11.669-2.863 3.866-1.908 6.849-4.533 8.949-7.875v9.021h15.607V78.182h-15.607v9.02zm-1.432 32.503c-.955 2.578-2.291 4.821-4.009 6.73-1.719 1.91-3.795 3.437-6.229 4.582-2.435 1.146-5.133 1.718-8.091 1.718-2.96 0-5.633-.572-8.019-1.718-2.387-1.146-4.438-2.672-6.156-4.582-1.719-1.909-3.032-4.152-3.938-6.73-.909-2.577-1.36-5.298-1.36-8.161 0-2.864.451-5.585 1.36-8.162.905-2.577 2.219-4.819 3.938-6.729 1.718-1.908 3.77-3.437 6.156-4.582 2.386-1.146 5.059-1.718 8.019-1.718 2.958 0 5.656.572 8.091 1.718 2.434 1.146 4.51 2.674 6.229 4.582 1.718 1.91 3.054 4.152 4.009 6.729.953 2.577 1.432 5.298 1.432 8.162 0 2.863-.479 5.584-1.432 8.161zM650.772 44.676h-15.606v100.23h15.606V44.676zM365.013 144.906h15.607V93.538h26.776V78.182h-42.383v66.724zM542.133 78.182l-19.616 51.096-19.616-51.096h-15.808l25.617 66.724h19.614l25.617-66.724h-15.808zM591.98 76.466c-19.112 0-34.239 15.706-34.239 35.079 0 21.416 14.641 35.079 36.239 35.079 12.088 0 19.806-4.622 29.234-14.688l-10.544-8.158c-.006.008-7.958 10.449-19.832 10.449-13.802 0-19.612-11.127-19.612-16.884h51.777c2.72-22.043-11.772-40.877-33.023-40.877zm-18.713 29.28c.12-1.284 1.917-16.884 18.589-16.884 16.671 0 18.697 15.598 18.813 16.884h-37.402zM184.068 43.892c-.024-.088-.073-.165-.104-.25-.058-.157-.108-.316-.191-.46-.056-.097-.137-.176-.203-.265-.087-.117-.161-.242-.265-.345-.085-.086-.194-.148-.29-.223-.109-.085-.206-.182-.327-.252l-.002-.001-.002-.002-35.648-20.524a2.971 2.971 0 00-2.964 0l-35.647 20.522-.002.002-.002.001c-.121.07-.219.167-.327.252-.096.075-.205.138-.29.223-.103.103-.178.228-.265.345-.066.089-.147.169-.203.265-.083.144-.133.304-.191.46-.031.085-.08.162-.104.25-.067.249-.103.51-.103.776v38.979l-29.706 17.103V24.493a3 3 0 00-.103-.776c-.024-.088-.073-.165-.104-.25-.058-.157-.108-.316-.191-.46-.056-.097-.137-.176-.203-.265-.087-.117-.161-.242-.265-.345-.085-.086-.194-.148-.29-.223-.109-.085-.206-.182-.327-.252l-.002-.001-.002-.002L40.098 1.396a2.971 2.971 0 00-2.964 0L1.487 21.919l-.002.002-.002.001c-.121.07-.219.167-.327.252-.096.075-.205.138-.29.223-.103.103-.178.228-.265.345-.066.089-.147.169-.203.265-.083.144-.133.304-.191.46-.031.085-.08.162-.104.25-.067.249-.103.51-.103.776v122.09c0 1.063.568 2.044 1.489 2.575l71.293 41.045c.156.089.324.143.49.202.078.028.15.074.23.095a2.98 2.98 0 001.524 0c.069-.018.132-.059.2-.083.176-.061.354-.119.519-.214l71.293-41.045a2.971 2.971 0 001.489-2.575v-38.979l34.158-19.666a2.971 2.971 0 001.489-2.575V44.666a3.075 3.075 0 00-.106-.774zM74.255 143.167l-29.648-16.779 31.136-17.926.001-.001 34.164-19.669 29.674 17.084-21.772 12.428-43.555 24.863zm68.329-76.259v33.841l-12.475-7.182-17.231-9.92V49.806l12.475 7.182 17.231 9.92zm2.97-39.335l29.693 17.095-29.693 17.095-29.693-17.095 29.693-17.095zM54.06 114.089l-12.475 7.182V46.733l17.231-9.92 12.475-7.182v74.537l-17.231 9.921zM38.614 7.398l29.693 17.095-29.693 17.095L8.921 24.493 38.614 7.398zM5.938 29.632l12.475 7.182 17.231 9.92v79.676l.001.005-.001.006c0 .114.032.221.045.333.017.146.021.294.059.434l.002.007c.032.117.094.222.14.334.051.124.088.255.156.371a.036.036 0 00.004.009c.061.105.149.191.222.288.081.105.149.22.244.314l.008.01c.084.083.19.142.284.215.106.083.202.178.32.247l.013.005.011.008 34.139 19.321v34.175L5.939 144.867V29.632h-.001zm136.646 115.235l-65.352 37.625V148.31l48.399-27.628 16.953-9.677v33.862zm35.646-61.22l-29.706 17.102V66.908l17.231-9.92 12.475-7.182v33.841z" />
                                </g>
                            </svg>
                            <div className="h-16 w-auto text-gray-500 text-3xl mt-5 ml-5 mr-5">
                                +
                            </div>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 180 180"
                                width="80"
                                className=" -mt-2">
                                <mask
                                    id="mask0_408_134"
                                    maskUnits="userSpaceOnUse"
                                    x="0"
                                    y="0"
                                    width="180"
                                    height="180"
                                    style={{ maskType: 'alpha' }}>
                                    <circle
                                        cx="90"
                                        cy="90"
                                        r="90"
                                        fill="black"
                                    />
                                </mask>
                                <g mask="url(#mask0_408_134)">
                                    <circle
                                        cx="90"
                                        cy="90"
                                        r="90"
                                        fill="black"
                                        data-circle="true"
                                    />
                                    <path
                                        d="M149.508 157.52L69.142 54H54V125.97H66.1136V69.3836L139.999 164.845C143.333 162.614 146.509 160.165 149.508 157.52Z"
                                        fill="url(#paint0_linear_408_134)"
                                    />
                                    <rect
                                        x="115"
                                        y="54"
                                        width="12"
                                        height="72"
                                        fill="url(#paint1_linear_408_134)"
                                    />
                                </g>
                                <defs>
                                    <linearGradient
                                        id="paint0_linear_408_134"
                                        x1="109"
                                        y1="116.5"
                                        x2="144.5"
                                        y2="160.5"
                                        gradientUnits="userSpaceOnUse">
                                        <stop stopColor="white" />
                                        <stop
                                            offset="1"
                                            stopColor="white"
                                            stopOpacity="0"
                                        />
                                    </linearGradient>
                                    <linearGradient
                                        id="paint1_linear_408_134"
                                        x1="121"
                                        y1="54"
                                        x2="120.799"
                                        y2="106.875"
                                        gradientUnits="userSpaceOnUse">
                                        <stop stopColor="white" />
                                        <stop
                                            offset="1"
                                            stopColor="white"
                                            stopOpacity="0"
                                        />
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                        <div className="text-white bg-black/60 px-20 py-10 rounded-lg shadow-lg">
                            {/* Main Message  */}
                            <div className="text-center">
                                <p className="text-4xl font-bold text-green-500 mb-10">
                                    🎉 Congratulations!
                                </p>
                                <p className="mt-2 text-lg text-white">
                                    Your brand-new, shiny{' '}
                                    <span className="font-semibold">
                                        Laravel Stack
                                    </span>{' '}
                                    is installed and ready to go.
                                </p>
                                <p className="mt-4 text-sm text-gray-100">
                                    Thank you for choosing{' '}
                                    <strong>Laravel Stack Installer</strong>.
                                    We’re excited to be part of your development
                                    journey. 🚀
                                </p>
                                <p className="mt-4 text-sm text-gray-100">
                                    As a quick start, we’ve added two
                                    directories for you:
                                    <code className="bg-gray-700 px-2 py-1 rounded">
                                        www
                                    </code>{' '}
                                    for your frontend files and
                                    <code className="bg-gray-700 px-2 py-1 rounded">
                                        API
                                    </code>{' '}
                                    for your backend files. The frontend is
                                    located in{' '}
                                    <code className="bg-gray-700 px-2 py-1 rounded">
                                        www/src/app/
                                    </code>{' '}
                                    and is built with Next.js. The backend is
                                    powered by Laravel. Navigate to these
                                    directories to get started with your
                                    project!
                                </p>
                            </div>

                            {/* Action Cards  */}
                            <div className="flex flex-wrap justify-center gap-6 mt-20">
                                {/* GitHub Card  */}
                                <div className="z-10 card flex flex-col items-center bg-white p-6 rounded-lg shadow-lg max-w-sm">
                                    <h3 className="text-xl font-semibold text-blue-500">
                                        ⭐ Star Us on GitHub
                                    </h3>
                                    <p className="mt-2 text-sm text-gray-600 text-center">
                                        Love the stack? Support us by starring
                                        our repository and contributing to its
                                        growth.
                                    </p>
                                    <a
                                        href="https://github.com/ngfw/laravel-stack"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="z-20 mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                        Visit GitHub
                                    </a>
                                </div>

                                {/*  Quick Start Card */}
                                <div className="z-10 card flex flex-col items-center bg-white p-6 rounded-lg shadow-lg max-w-sm">
                                    <h3 className="text-xl font-semibold text-green-500">
                                        🚀 Quick Start
                                    </h3>
                                    <p className="mt-2 text-sm text-gray-600 text-center">
                                        Ready to dive in? <br />
                                        Head to your
                                        <code className="bg-gray-200 px-2 py-1 rounded">
                                            www/src/app/
                                        </code>
                                        directory and start building something
                                        amazing!
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div className="flex justify-center mt-4 sm:items-center sm:justify-between">
                            <div className="text-center text-sm text-gray-500 sm:text-left"></div>

                            <div className="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                                Laravel Breeze + Next.js template
                            </div>
                        </div>

                        <ul className="circles">
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>
    )
}

export default LaravelStack
