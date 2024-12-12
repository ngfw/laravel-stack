import React, { FC, useEffect, useState, useRef, memo } from "react";
import { Head } from "@inertiajs/react";
import { confetti } from "@tsparticles/confetti";

const Home: FC = () => {

  useEffect(() => {
    confetti({
      particles: 100,
      angle: 90,
      spread: 50,
      origin: { x: 0.5, y: 0.5 },
    });
  }, []);

  return (
    <main className="app flex items-center justify-between">
      <Head title="Home" />
      <div className="home-page z-10 flex-1">
        <section>
          <div className="text-white bg-black/60 px-20 py-10 rounded-lg shadow-lg">
          
    {/* Main Message */}
    <div className="text-center">
      <p className="text-4xl font-bold text-green-500 mb-10">
        🎉 Congratulations!
      </p>
      <p className="mt-2 text-lg text-white">
        Your brand-new, shiny <span className="font-semibold">Laravel Stack</span> is installed and ready to go.
      </p>
      <p className="mt-4 text-sm text-gray-100">
        Thank you for choosing <strong>Laravel Stack Installer</strong>. We’re excited to be part of your development journey. 🚀
      </p>
      <p className="mt-4 text-sm text-gray-100">
        As a quick start, we’ve added a <code className="bg-gray-700 px-2 py-1 rounded">react</code> directory in your <code className="bg-gray-700 px-2 py-1 rounded">/resources</code> folder. Navigate there to get started with your project!
      </p>
    </div>

    {/* Action Cards */}
    <div className="flex flex-wrap justify-center gap-6 mt-20">
      {/* GitHub Card */}
      <div className="z-10 card flex flex-col items-center bg-white p-6 rounded-lg shadow-lg max-w-sm">
        <h3 className="text-xl font-semibold text-blue-500">⭐ Star Us on GitHub</h3>
        <p className="mt-2 text-sm text-gray-600 text-center">
          Love the stack? Support us by starring our repository and contributing to its growth.
        </p>
        <a
          href="https://github.com/ngfw/laravel-stack"
          target="_blank"
          rel="noopener noreferrer"
          className="z-20 mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
        >
          Visit GitHub
        </a>
      </div>

      {/* Quick Start Card */}
      <div className="z-10 card flex flex-col items-center bg-white p-6 rounded-lg shadow-lg max-w-sm">
        <h3 className="text-xl font-semibold text-green-500">🚀 Quick Start</h3>
        <p className="mt-2 text-sm text-gray-600 text-center">
          Ready to dive in? <br />Head to your <code className="bg-gray-200 px-2 py-1 rounded">/resources/react</code> directory and start building something amazing!
        </p>
       
      </div>
    </div>
  
          </div>
          <ul className="circles">
            {Array.from({ length: 10 }, (_, i) => (
              <li key={i}></li>
            ))}
          </ul>
        </section>
      </div>
    </main>
  );
};

export default Home;
