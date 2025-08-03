import React, { useEffect, useState } from 'react';

export default function Timer({ duration, onExpire }) {
  const [secondsLeft, setSecondsLeft] = useState(duration * 60);

  useEffect(() => {
    if (!secondsLeft) return;

    const timer = setInterval(() => {
      setSecondsLeft(prev => {
        if (prev <= 1) {
          clearInterval(timer);
          onExpire(); // appelle de la fonction passée en prop
          return 0;
        }
        return prev - 1;
      });
    }, 1000);

    return () => clearInterval(timer);
  }, [secondsLeft]);

  const formatTime = (seconds) => {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m}:${s < 10 ? '0' : ''}${s}`;
  };

  const isCritical = secondsLeft <= 30;

  return (
     <div
     className={`fixed top-5 right-5 px-5 py-3 font-semibold font-mono flex items-center space-x-3 cursor-default
        ${isCritical ? ' text-red-500 animate-pulse border-2 rounded-full border-red-500' : 'text-orange-600'}
      `}
      title="Temps restant"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        className="h-6 w-6 animate-spin-slow"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        strokeWidth={2}
      >
        <path strokeLinecap="round" strokeLinejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span className="text-lg tracking-wide">{formatTime(secondsLeft)}</span>
    </div>
  );
}
