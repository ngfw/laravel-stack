import React, { FC, useEffect, useState, useRef, memo } from "react";
import { Head } from "@inertiajs/react";

const COLORS = ["#2ecc71", "#3498db", "#e67e22", "#e74c3c"];
const LEFT_OFFSET = 150;

// Utility functions
const randomNumber = (min: number, max: number): number =>
  min + Math.floor(Math.random() * (max - min));

const randomColor = (): string => COLORS[randomNumber(0, COLORS.length)];

interface ParticleProps {
  children: React.ReactElement;
  size: [number, number];
}

const Particle: FC<ParticleProps> = ({ children, size }) => {
  const ref = useRef<HTMLDivElement | SVGElement>(null);
  const child = React.Children.only(children);
  const top = randomNumber(-200, -(size?.[1] || 10));

  useEffect(() => {
    if (ref.current) {
      ref.current.style.setProperty("--x", `${randomNumber(-LEFT_OFFSET, LEFT_OFFSET)}px`);
      ref.current.style.setProperty("--y", `${window.innerHeight - top + randomNumber(0, 300)}px`);
      ref.current.style.setProperty("--rotate", `${randomNumber(200, 3000)}deg`);
    }
  }, []);

  return React.cloneElement(child, {
    ref,
    style: {
      "--color": randomColor(),
      "--size": `${randomNumber(...size)}px`,
      "--rotate": "0deg",
      "--x": "0px",
      "--y": "0px",
      top: `${top}px`,
      left: `${randomNumber(0, window.innerWidth)}px`,
    } as React.CSSProperties,
  });
};

const CircularParticle: FC = () => (
  <Particle size={[5, 10]}>
    <div className="particle circular" />
  </Particle>
);

const RectangularParticle: FC = () => (
  <Particle size={[5, 10]}>
    <div className="particle rectangular" />
  </Particle>
);

const SquiggleParticle: FC = () => (
  <Particle size={[15, 45]}>
    <svg
      className="particle squiggle"
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 30 200"
    >
      <path d="M15 0 Q 30 25 15 50 Q 0 75 15 100 Q 30 125 15 150 Q 0 175 15 200" />
    </svg>
  </Particle>
);

interface ParticlesProps {
  count: number;
}

const Particles: FC<ParticlesProps> = memo(({ count }) => {
  const types = [SquiggleParticle, RectangularParticle, CircularParticle];
  const particles = Array.from({ length: count }, (_, i) => {
    const ParticleComponent = types[randomNumber(0, 3)];
    return <ParticleComponent key={i} />;
  });

  return <div className="particles">{particles}</div>;
});

const Home: FC = () => {
  const [particles, setParticles] = useState<number[]>([]);
  const idRef = useRef<number>(1);
  const [innerWidth, setInnerWidth] = useState<number>(0);

  useEffect(() => {
    setInnerWidth(window.innerWidth);
  
    
      const _id = idRef.current;
      idRef.current++;
  
      setParticles((prevParticles) => {
        const updatedParticles = [...prevParticles, _id];
        return updatedParticles.length > 300 ? updatedParticles.slice(-300) : updatedParticles;
      });
  
      setTimeout(() => {
        setParticles((prevParticles) => prevParticles.filter((id) => id !== _id));
      }, 5000); 
    
  
    
  }, []);

  return (
    <main className="app flex items-center justify-between">
      <div className="tadaa">
      {particles.map((id) => (
        <Particles key={id} count={Math.floor(innerWidth / 10)} />
      ))}
      </div>
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
